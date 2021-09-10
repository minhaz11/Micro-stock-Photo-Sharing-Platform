<?php

namespace App\Http\Controllers\Contributor;

use App\User;
use App\Image;
use App\Follow;
use App\Download;
use App\Contributor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContributorController extends Controller
{
 

    public function followers()
    {
        $page_title = 'Your Followers';
        $empty_message = "No followers";
        $followersU = Follow::whereFollowed(auth()->id())->whereNotNull('user_id')->whereRemoved(0)->get();

        $users = collect([]);
        
        $followersU->map(function($item) use ($users){
            $users->push(User::findOrFail($item->user_id));
        });
       
        $followerUsers = $users->paginate(10);
        return view(activeTemplate().'contributor.follower.all',compact('followerUsers','page_title','empty_message'));
       
    }
    public function followersSearch(Request $request)
    {
        $search = $request->search;
        $page_title = "Search results of $search";
        $empty_message = "No results found";
        $followersU = Follow::whereFollowed(auth()->id())->whereNotNull('user_id')->whereRemoved(0)->get();

        $users = collect([]);
        
        $followersU->map(function($item) use ($users){
            $users->push(User::findOrFail($item->user_id));
        });
       
        $followerUsers = $users->filter(function($item) use ($search){
            return false !== stristr($item->username, $search);
        })->paginate(10);
        return view(activeTemplate().'contributor.follower.all',compact('followerUsers','page_title','empty_message','search'));
    }

    public function followerRemove($follower,$followed)
    {
        
        $update =Follow::whereUserId($follower)->whereFollowed($followed)->first();
        $update->removed = 1;
        $update->update();
        
        $notify[]=['success','Follower removed successfully'];
        return back()->withNotify($notify);
    }

   
}
