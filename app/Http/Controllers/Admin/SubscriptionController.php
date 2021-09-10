<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function all()
    {
        $page_title = "Subscriptions";
        $empty_message = "No data";
        $subscriptions = Subscription::latest()->paginate(15);
        return view('admin.subscription.all',compact('page_title','subscriptions','empty_message'));

    }

    public function create(Request $request)
    {
        
        $request->validate([
            'icon' => 'required|string',
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'daily_limit' => 'required|numeric|min:1',
            'monthly_limit' => 'required|numeric|min:1',
            'yearly_limit' => 'required|numeric|min:1',
            'facilities' => 'required',
            'facilities.*' => 'required',
        ]);

        $sbc = new Subscription();
        $sbc->icon = $request->icon;
        $sbc->name = $request->name;
        $sbc->price = $request->price;
        $sbc->daily_limit = $request->daily_limit;
        $sbc->monthly_limit = $request->monthly_limit;
        $sbc->yearly_limit = $request->yearly_limit;
        $sbc->facilities = $request->facilities;
        $sbc->status =  $sbc->status ? 1 : 0;
        $sbc->save();

       $notify[]=['success','Subscription created successfully'];
       return back()->withNotify($notify);

    }

    public function edit($id)
    {
        $subscription = Subscription::findOrFail($id);
        $page_title='Update Subscription';
        return view('admin.subscription.edit',compact('subscription','page_title'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'icon' => 'required|string',
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'daily_limit' => 'required|numeric|min:1',
            'monthly_limit' => 'required|numeric|min:1',
            'yearly_limit' => 'required|numeric|min:1',
            'facilities' => 'required',
            'facilities.*' => 'required',
        ]);

        $subscription = Subscription::findOrFail($request->id);
        $subscription->icon = $request->icon;
        $subscription->name = $request->name;
        $subscription->price = $request->price;
        $subscription->daily_limit = $request->daily_limit;
        $subscription->monthly_limit = $request->monthly_limit;
        $subscription->yearly_limit = $request->yearly_limit;
        $subscription->facilities = $request->facilities;
        $subscription->status =  $request->status ? 1 : 0;
        $subscription->update();
        $notify[]=['success','Subscription updated successfully'];
        return back()->withNotify($notify);
        
    }
}
