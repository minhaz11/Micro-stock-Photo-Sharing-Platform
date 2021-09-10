<?php

namespace App\Http\Controllers\User;


use App\User;
use App\Image;
use App\Follow;
use App\Download;
use App\EarningLog;
use App\GeneralSetting;
use Carbon\Carbon;
use App\Subscription;
use App\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Transaction;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{

    public function __construct() {
        $this->activeTemplate = activeTemplate();
    }

    public function home()
    {

        $user = auth()->user();
        $page_title = 'User Dashboard';
        $data['totalEarnings'] = $user->earnings->sum('amount');
        $data['totalWithdraw'] = $user->withdrawals->where('status',1)->sum('amount');
        $data['likes'] = $user->likeCount();
        $data['totalDownloads'] = $user->downloadCount();


        $data['totalCollection'] =  $user->collections()->count();
        $data['totalFollows'] =  $user->follows()->count();
        $data['totalFollower'] =  Follow::whereFollowed(auth()->id())->count();
        $data['referredUsers']= User::where('ref_by',auth()->id())->count();

        $data['totalPhotos'] =  $user->images->where('status',1)->count();
        $data['totalPendingPhotos'] =  $user->images->where('status',0)->count();
        $data['totalBannedPhotos'] =  $user->images->where('status',2)->count();
        $data['todaysEarning'] =  $user->earnings->where('date',Carbon::now()->toDateString())->sum('amount');


        //graph
        $report['downloadMonths'] = collect([]);
        $report['download'] = collect([]);
        $report['downloadTotal'] = collect([]);

        $report['earnings'] = collect([]);
        $report['earning_month'] = collect([]);

        $totalDownloads = Download::whereContributorId($user->id)->whereYear('created_at', '>=', Carbon::now()->subYear())
            ->selectRaw("COUNT( CASE WHEN premium = 1 THEN contributor_id END) as total")
            ->selectRaw("DATE_FORMAT(created_at,'%M') as months")
            ->orderBy('created_at')
            ->groupBy(DB::Raw("MONTH(created_at)"))->get();

        $totalDownloads->map(function($item) use ($report){
            $report['downloadTotal']->push($item->total);
            $report['downloadMonths']->push($item->months);
        });


        $totalEarnings = EarningLog::whereYear('created_at', '>=', Carbon::now()->subYear())
            ->selectRaw("SUM( CASE WHEN contributor_id = $user->id THEN amount END) as total")
            ->selectRaw("DATE_FORMAT(created_at,'%M') as months")
            ->orderBy('created_at')
            ->groupBy(DB::Raw("MONTH(created_at)"))->get();
        $totalEarnings->map(function ($bb) use ($report){
            $report['earnings']->push(getAmount($bb->total));
            $report['earning_month']->push($bb->months);
        });


        $approveImages = Image::whereStatus(1)->whereUserId($user->id)->latest()->take(7)->get();
        return view($this->activeTemplate . 'user.dashboard', compact('page_title','data','user','report','approveImages'));
    }
    public function downloads()
    {
        $data['page_title'] = "Download Records";
        $data['empty_message'] = "No downloads";
        $downloads = Download::with('image')->whereHas('image')->whereUserId(auth()->id());

        $user = auth()->user();
        if($user->subscription){

            $purchaseAt = showDateTime($user->subscription->purchase_at,'Y-m-d');
            $expireAt = showDateTime($user->subscription->expire_at,'Y-m-d');

            $data['yearlyCount'] = $downloads->where('premium',1)->whereBetween('date',[$purchaseAt, $expireAt])->count();
            $data['monthlyCount'] = $downloads->where('premium',1)->whereMonth('date',Carbon::now()->month)->count();
            $data['dailyCount'] = $downloads->where('premium',1)->whereDay('date',Carbon::now()->today())->count();
        }

        $data['downloads'] = $downloads->latest()->paginate(15);
        return view($this->activeTemplate.'user.download.all',$data);
    }

    public function following()
    {

        $following = Follow::whereUserId(auth()->id())->get();
        $page_title='You are following';
        $empty_message="You are not following anyone";
        $followed['followed'] = collect([]);
        $following->map(function($item) use ($followed){
            $followed['followed']->push(User::findOrFail($item->followed));

        });
        $data =  $followed['followed']->paginate(15);
        return view($this->activeTemplate.'user.following.all',compact('data','page_title','empty_message'));
    }

    public function followingSearch(Request $request)
    {
        $search = $request->search;

        $following = Follow::whereUserId(auth()->id())->get();
        $page_title="Search results of $search";
        $empty_message="No results found";
        $followed['followed'] = collect([]);

        $following->map(function($item) use ($followed){
            $followed['followed']->push(User::findOrFail($item->followed));

        });

        $data = $followed['followed']->filter(function($item) use ($search){
            return false !== stristr($item->username, $search);
        })->paginate(15);
        return view($this->activeTemplate.'user.following.all',compact('data','page_title','empty_message','search'));

    }

    public function likedPhotos()
    {
        $page_title = "Your liked photos";
        $empty_message = "No Liked Photos";
        $liked['liked'] = collect([]);
        auth()->user()->likes->map(function($item) use ($liked){
            $img = Image::find($item->image_id);
            if($img)
            $liked['liked']->push($img);
        });
        $photos = $liked['liked']->paginate(12);
        return view($this->activeTemplate.'user.liked.all',compact('page_title','empty_message','photos'));
    }

    public function profile()
    {
        $profile = User::findOrFail(Auth::id());
        $page_title = "Profile";
        return view($this->activeTemplate.'user.profile',compact('profile','page_title'));
    }
    public function profileUpdate(Request $request)
    {

        $request->validate([
            'fname'=>'required',
            'lname'=>'required',
            'city' =>'required',
            'image' => 'image|mimes:jpeg,jpg|max:2048'
        ]);
        $profile = User::findOrFail(Auth::id());
        $profile->firstname = $request->fname;
        $profile->lastname = $request->lname;
        $profile->address =[
            'city' => $request->city
        ];

         if ($request->hasFile('image')) {
            try {
                $old = $profile->image ?: null;
                $profile->image = uploadImage($request->image, 'assets/user/profile/', '400X400', $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Image could not be uploaded.'];
                return back()->withNotify($notify);
            }
        }

        $profile->update();
        $notify[]=['success','Profile updated successfully'];
        return back()->withNotify($notify);

    }

    public function migrate(Request $request)
     {
        auth()->user()->con_flag = 1;
        auth()->user()->update();

        $notify[]=['success','You have successfully migrated as a contributor'];
        return back()->withNotify($notify);
    }

    public function subscriptions()
    {
        $page_title = "Subscriptions";
        $empty_message = "No data";

        $subscriptions = Subscription::where('status',1)->get();
        return view($this->activeTemplate.'user.pricing.subscriptions',compact('page_title','empty_message','subscriptions'));
    }

    public function purchaseSubscription($id)
    {
        $user = auth()->user();
        $plan = Subscription::find($id);
        if($plan->price > $user->balance){
            $notify[]=['error','Sorry insufficient balance'];
            return back()->withNotify($notify);
        }
        if(!$plan){
            $notify[]=['error','Sorry plan doesn\'t exist'];
            return back()->withNotify($notify);
        }
        $page_title = 'Purchase Preview';
       return view($this->activeTemplate.'user.pricing.purchasePreview',compact('page_title','plan'));
    }


    public function purchaseSubscriptionConfirm(Request $request)
    {

        $user = auth()->user();
        $gnl = GeneralSetting::first();
        $plan = Subscription::find($request->planid);
        if($plan==null){
            $notify[]=['error','No subscription plan found'];
            return back()->withNotify($notify);
        }
        if($user->balance < $plan->price){
            $notify[]=['error','Sorry insufficient balance'];
            return back()->withNotify($notify);
        }
        $subscription = new UserSubscription();
        $subscription->user_id = $user->id;
        $subscription->subscription_id = $plan->id;
        $subscription->purchase_at = Carbon::now();
        $subscription->expire_at = Carbon::now()->addYear(1);
        $subscription->save();

        $user->balance -= $plan->price;
        $user->update();

        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->amount = $plan->price;
        $transaction->post_balance = getAmount($user->balance);
        $transaction->charge = 0;
        $transaction->trx_type = '-';
        $transaction->details = 'Purchased Subscription '. $subscription->mainData->name;
        $transaction->trx = getTrx();
        $transaction->save();

        if($user->ref_by!=null){
            $refUser = User::find($user->ref_by);
            if($refUser){
                $bonus = $plan->price*($gnl->ref_bonus/100);
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

        notify($user, 'SUBSCRIPTION', [
            'method_name' => 'Balance',
            'subscription_name'=> $plan->name,
            'amount' => getAmount($plan->price),
            'charge' => 0,
            'currency' => $gnl->cur_text,
            'trx' => $transaction->trx
        ]);

        $notify[]=['success','Subscription purchased successfully'];
        return redirect(route('user.subscriptions'))->withNotify($notify);


    }

    public function feed()
    {

        $user = auth()->user();
        $page_title = "Photos from users that you are following";
        $empty_message = "No feeds found";
        $feedImages = collect([]);

        if(!$user->follows->isEmpty()){
            $user->follows->map(function($item) use ($feedImages){
                $feedImages->push(User::findOrFail($item->followed)->latestImages);
            });
            return view($this->activeTemplate.'frontend.feed',compact('page_title','empty_message','feedImages'));
        }

        $notify[]=['error','Currently you are not following anyone'];
        return back()->withNotify($notify);
    }

    public function trxLogs()
    {
        $trxs = Transaction::whereUserId(auth()->id())->latest()->paginate(getPaginate());
        $page_title = "Transaction Logs";
        $empty_message = "No data";
        return view($this->activeTemplate.'user.transaction.logs',compact('trxs','page_title','empty_message'));
    }

    public function referredUsers()
    {
        $page_title = "Referred Users";
        $referredUsers = User::where('ref_by',auth()->id())->latest()->paginate(getPaginate());
        $empty_message = "No referred users";
        return view($this->activeTemplate.'user.referral.referredUsers',compact('page_title','referredUsers','empty_message'));
    }

    public function referralCommissions()
    {
        $page_title = "Referral Commissions";
        $commissions = Transaction::where('user_id',auth()->id())->where('remark','referral')->latest()->paginate(getPaginate());
        $empty_message = "No referral commissions";
        return view($this->activeTemplate.'user.referral.commissionLog',compact('page_title','commissions','empty_message'));
    }

    public function earningLogs()
    {
        $page_title = 'Earning Logs';
        $logs = EarningLog::where('contributor_id',auth()->id())->whereHas('image')->latest()->paginate(getPaginate());
        $empty_message = "No Earning Logs";
        return view($this->activeTemplate.'user.earningLogs',compact('page_title','logs','empty_message'));
    }



}
