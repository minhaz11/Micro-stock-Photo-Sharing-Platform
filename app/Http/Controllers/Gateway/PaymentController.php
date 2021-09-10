<?php

namespace App\Http\Controllers\Gateway;

use App\User;
use App\Deposit;
use App\Transaction;
use App\GeneralSetting;
use App\GatewayCurrency;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Subscription;
use App\UserSubscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Route;
class PaymentController extends Controller
{
    public function __construct()
    {
        return $this->activeTemplate = activeTemplate();
    }

    public function deposit($id=null)
    {
        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', 1);
        })->with('method')->orderby('method_code')->get();
        $page_title = 'Deposit Methods';

        if(Route::current()->getName()=='user.payment'){
            $page_title = 'Payment methods';
            $plan = Subscription::findOrFail($id);
            Session::put('pricePlan',$plan);
        }
        return view($this->activeTemplate . 'user.payment.deposit', compact('gatewayCurrency', 'page_title'));
    }

    public function depositInsert(Request $request)
    {
        $pricePlan = session()->get('pricePlan');
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'method_code' => 'required',
            'currency' => 'required',
        ]);

        if(isset($pricePlan) && $pricePlan->price != $request->amount){
            $notify[]=['error','Sorry Amount mismatch'];
            return back()->withNotify($notify);
        }

        $user = auth()->user();
        $gate = GatewayCurrency::where('method_code', $request->method_code)->where('currency', $request->currency)->first();
        if (!$gate) {
            $notify[] = ['error', 'Invalid Gateway'];
            return back()->withNotify($notify);
        }

        if ($gate->min_amount > $request->amount || $gate->max_amount < $request->amount) {
            $notify[] = ['error', 'Please Follow Payment Limit'];
            return back()->withNotify($notify);
        }

        $charge = getAmount($gate->fixed_charge + ($request->amount * $gate->percent_charge / 100));
        $payable = getAmount($request->amount + $charge);
        $final_amo = getAmount($payable * $gate->rate);

        $depo['user_id'] = $user->id;
        $depo['method_code'] = $gate->method_code;
        $depo['method_currency'] = strtoupper($gate->currency);
        $depo['amount'] = $request->amount;
        $depo['charge'] = $charge;
        $depo['rate'] = $gate->rate;
        $depo['final_amo'] = getAmount($final_amo);
        $depo['btc_amo'] = 0;
        $depo['btc_wallet'] = "";
        $depo['trx'] = getTrx();
        $depo['try'] = 0;
        $depo['status'] = 0;
        $pricePlan? $depo['payment'] = 1:$depo['payment'] = null;
        $data = Deposit::create($depo);
        session()->put('Track', $data['trx']);
        if($pricePlan){
            return redirect()->route('user.payment.preview');
        }
        return  redirect()->route('user.deposit.preview');
    }


    public function depositPreview()
    {

        $track = session()->get('Track');
        $data = Deposit::where('trx', $track)->orderBy('id', 'DESC')->firstOrFail();
        if (is_null($data)) {
            $notify[] = ['error', 'Invalid Peyment Request'];
            return redirect()->route(gatewayRedirectUrl())->withNotify($notify);
        }
        if ($data->status != 0) {
            $notify[] = ['error', 'Invalid Peyment Request'];
            return redirect()->route(gatewayRedirectUrl())->withNotify($notify);
        }
        $page_title = 'Payment Preview';
        return view($this->activeTemplate . 'user.payment.preview', compact('data', 'page_title'));
    }


    public function depositConfirm()
    {
        $track = Session::get('Track');
        $deposit = Deposit::where('trx', $track)->orderBy('id', 'DESC')->with('gateway')->first();
        if (is_null($deposit)) {
            $notify[] = ['error', 'Invalid Payment Request'];
            return redirect()->route(gatewayRedirectUrl())->withNotify($notify);
        }
        if ($deposit->status != 0) {
            $notify[] = ['error', 'Invalid Payment Request'];
            return redirect()->route(gatewayRedirectUrl())->withNotify($notify);
        }

        if ($deposit->method_code >= 1000) {
            $this->userDataUpdate($deposit);
            $notify[] = ['success', 'Your payment request is queued for approval.'];
            return back()->withNotify($notify);
        }


        $dirName = $deposit->gateway->alias;
        $new = __NAMESPACE__ . '\\' . $dirName . '\\ProcessController';

        $data = $new::process($deposit);
        $data = json_decode($data);


        if (isset($data->error)) {
            $notify[] = ['error', $data->message];
            return redirect()->route(gatewayRedirectUrl())->withNotify($notify);
        }
        if (isset($data->redirect)) {
            return redirect($data->redirect_url);
        }

        // for Stripe V3
        if(@$data->session){
            $deposit->btc_wallet = $data->session->id;
            $deposit->save();
        }

        $page_title = 'Payment Confirm';
        return view($this->activeTemplate . $data->view, compact('data', 'page_title', 'deposit'));
    }


    public static function userDataUpdate($trx)
    {
        $plan = session()->get('pricePlan');
        $gnl = GeneralSetting::first();
        $data = Deposit::where('trx', $trx)->first();
        if ($data->status == 0) {
            $data['status'] = 1;
            $data->update();

                $user = User::find($data->user_id);
                $user->balance += $data->amount;
                $user->save();

                $gateway = $data->gateway;
                $transaction = new Transaction();
                $transaction->user_id = $data->user_id;
                $transaction->amount = $data->amount;
                $transaction->post_balance = getAmount($user->balance);
                $transaction->charge = getAmount($data->charge);
                $transaction->trx_type = '+';
                $transaction->details = 'Deposit Via ' . $gateway->name;
                $transaction->trx = $data->trx;
                $transaction->save();


            if($plan){

                $user->balance -= $data->amount;
                $user->update();

                $subscription = new UserSubscription();
                $subscription->user_id = $user->id;
                $subscription->subscription_id = $plan->id;
                $subscription->purchase_at = Carbon::now();
                $subscription->expire_at = Carbon::now()->addYear(1);
                $subscription->save();

                $transaction = new Transaction();
                $transaction->user_id = $data->user_id;
                $transaction->amount = $data->amount;
                $transaction->post_balance = getAmount($user->balance);
                $transaction->charge = 0;
                $transaction->trx_type = '-';
                $transaction->details =  'Purchased Subscription '. $subscription->mainData->name;
                $transaction->trx = $data->trx;
                $transaction->save();


                if($user->ref_by!=null){
                    $refUser = User::find($user->ref_by);
                    if($refUser){
                        $bonus = $data->amount*($gnl->ref_bonus/100);
                        $refUser->balance += $bonus;
                        $refUser->update();

                        $trx = new Transaction();
                        $trx->user_id = $refUser->id;
                        $trx->amount =  $bonus;
                        $trx->post_balance = getAmount($refUser->balance);
                        $trx->charge = 0;
                        $trx->trx_type = '+';
                        $trx->details = 'Referral Bonus from '."$user->username";
                        $trx->remark = 'referral';
                        $trx->trx = getTrx();
                        $trx->save();

                        notify($refUser,'REF_BONUS',[
                            'referral' => $user->username,
                            'amount' => $bonus,
                            'currency' => $gnl->cur_text,
                            'post_balance' => $refUser->balance,
                            'trx' => $trx->trx
                        ]);
                    }

                }

            }

            if($plan){
                notify($data->user, 'SUBSCRIPTION', [
                    'method_name' => $data->gateway_currency()->name,
                    'subscription_name'=> $plan->name,
                    'amount' => getAmount($data->amount),
                    'charge' => getAmount($data->charge),
                    'currency' => $gnl->cur_text,
                    'trx' => $data->trx
                ]);
            } else {
                notify($user, 'DEPOSIT_COMPLETE', [
                    'method_name' => $data->gateway_currency()->name,
                    'method_currency' => $data->method_currency,
                    'method_amount' => getAmount($data->final_amo),
                    'amount' => getAmount($data->amount),
                    'charge' => getAmount($data->charge),
                    'currency' => $gnl->cur_text,
                    'rate' => getAmount($data->rate),
                    'trx' => $data->trx,
                    'post_balance' => getAmount($user->balance)
                ]);

            }


        }
    }

    public function manualDepositConfirm()
    {
        $track = session()->get('Track');
        $data = Deposit::with('gateway')->where('status', 0)->where('trx', $track)->first();
        if (!$data) {
            return redirect()->route(gatewayRedirectUrl());
        }
        if ($data->status != 0) {
            return redirect()->route(gatewayRedirectUrl());
        }
        if ($data->method_code > 999) {

            $page_title = session('pricePlan') ? 'Deposit Confirm':'Payment Confirm';
            $method = $data->gateway_currency();
            return view($this->activeTemplate . 'user.manual_payment.manual_confirm', compact('data', 'page_title', 'method'));
        }
        abort(404);
    }

    public function manualDepositUpdate(Request $request)
    {
        $track = session()->get('Track');
        $data = Deposit::with('gateway')->where('status', 0)->where('trx', $track)->first();
        if (!$data) {
            return redirect()->route(gatewayRedirectUrl());
        }
        if ($data->status != 0) {
            return redirect()->route(gatewayRedirectUrl());
        }

        $params = json_decode($data->gateway_currency()->gateway_parameter);

        $rules = [];
        $inputField = [];
        $verifyImages = [];

        if ($params != null) {
            foreach ($params as $key => $cus) {
                $rules[$key] = [$cus->validation];
                if ($cus->type == 'file') {
                    array_push($rules[$key], 'image');
                    array_push($rules[$key], 'mimes:jpeg,jpg,png');
                    array_push($rules[$key], 'max:2048');

                    array_push($verifyImages, $key);
                }
                if ($cus->type == 'text') {
                    array_push($rules[$key], 'max:191');
                }
                if ($cus->type == 'textarea') {
                    array_push($rules[$key], 'max:300');
                }
                $inputField[] = $key;
            }
        }


        $this->validate($request, $rules);


        $directory = date("Y")."/".date("m")."/".date("d");
        $path = imagePath()['verify']['deposit']['path'].'/'.$directory;
        $collection = collect($request);
        $reqField = [];
        if ($params != null) {
            foreach ($collection as $k => $v) {
                foreach ($params as $inKey => $inVal) {
                    if ($k != $inKey) {
                        continue;
                    } else {
                        if ($inVal->type == 'file') {
                            if ($request->hasFile($inKey)) {
                                try {
                                    $reqField[$inKey] = [
                                        'field_name' => $directory.'/'.uploadImage($request[$inKey], $path),
                                        'type' => $inVal->type,
                                    ];
                                } catch (\Exception $exp) {
                                    $notify[] = ['error', 'Could not upload your ' . $inKey];
                                    return back()->withNotify($notify)->withInput();
                                }
                            }
                        } else {
                            $reqField[$inKey] = $v;
                            $reqField[$inKey] = [
                                'field_name' => $v,
                                'type' => $inVal->type,
                            ];
                        }
                    }
                }
            }
            $data->detail = $reqField;
        } else {
            $data->detail = null;
        }


        $plan = session('pricePlan');
        $data->status = 2; // pending
        $data->plan_id =  $plan ? $plan->id:null;
        $data->update();

        $gnl = GeneralSetting::first();


        if($plan){
            notify($data->user, 'SUBSCRIPTION_REQ', [
                'method_name' => $data->gateway_currency()->name,
                'subscription_name'=> $plan->name,
                'method_currency' => $data->method_currency,
                'method_amount' => getAmount($data->final_amo),
                'amount' => getAmount($data->amount),
                'charge' => getAmount($data->charge),
                'currency' => $gnl->cur_text,
                'rate' => getAmount($data->rate),
                'trx' => $data->trx
            ]);
            $notify[] = ['success', 'Your subscription purchase request has been taken.'];
            $route = 'user.subscriptions';

        } else {
            notify($data->user, 'DEPOSIT_REQUEST', [
                'method_name' => $data->gateway_currency()->name,
                'method_currency' => $data->method_currency,
                'method_amount' => getAmount($data->final_amo),
                'amount' => getAmount($data->amount),
                'charge' => getAmount($data->charge),
                'currency' => $gnl->cur_text,
                'rate' => getAmount($data->rate),
                'trx' => $data->trx
            ]);
            $notify[] = ['success', 'Your deposit request has been taken.'];
            $route = 'user.deposit.history';
        }

        return redirect()->route($route)->withNotify($notify);
    }


}
