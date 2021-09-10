<?php

namespace App\Http\Controllers\Admin;

use App\Badge;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BadgeController extends Controller
{
    public function all()
    {
        $page_title = "All Badges";
        $empty_message = "No data";

        $badges = Badge::latest()->paginate(15);
        return view('admin.badge.all',compact('page_title','empty_message','badges'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'icon' => 'required|image|mimes:png,PNG|max:1024',
            'name' => 'required|string',
            'milestone' => 'required|numeric|min:1'
        ]);

        $badge = new Badge();
       
        if($request->icon){
            $badge->icon = uploadImage($request->icon,'assets/images/badge/','150x150');
        }
        $badge->name = $request->name;
        $badge->download_milestone = $request->milestone;
        $badge->status = $request->status ? 1:0;
       
        $notify[]=['success','Badge created  successfully'];
        return back()->withNotify($notify);
    }
    public function update(Request $request)
    {
        $request->validate([
            'icon' => 'required|image|mimes:png,PNG|max:1024',
            'name' => 'required|string',
            'milestone' => 'required|numeric|min:1'
        ]);
        $badge =  Badge::findOrfail($request->id);
        if($request->icon){
            $old = $badge->icon ?? null;
            $badge->icon = uploadImage($request->icon,'assets/images/badge/','150x150',$old);
        }
        $badge->name = $request->name;
        $badge->download_milestone = $request->milestone;
        $badge->status = $request->status ? 1 : 0;
        $badge->update();
 
        $notify[]=['success','Badge Updated  successfully'];
        return back()->withNotify($notify);
    }

   
}
