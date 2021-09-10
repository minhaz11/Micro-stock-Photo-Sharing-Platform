<?php

namespace App\Http\Controllers;


use App\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CollectionController extends Controller
{
   
    public function store(Request $request)
    {
       
        $request->validate([
            'title'=>'required',
            'description'=>'required',
            'photo' =>'image|max:2048'
        ]);

        $collection = new Collection();
        $collection->user_id = auth()->id();
        
         if ($request->hasFile('photo')) {
            try {
                $old = $collection->image ?: null;
                $collection->image = uploadImage($request->photo, 'assets/collection/cover/', '320X350', $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Image could not be uploaded.'];
                return back()->withNotify($notify);
            }
        }
        $collection->title = $request->title;
        $collection->description = $request->description;
        $collection->save();
        if($request->wantsJson()){
            return response()->json(['success' => 'New collection added']);
        }
        $notify[]=['success','Collection added successfully'];
        return back()->withNotify($notify);
    }

    public function collectionUpdate(Request $request,$id)
    {
       
        $request->validate([
            'title'=>'required',
            'description'=>'required',
            'image' =>'image|max:2048'
        ]);

        $collection = Collection::findOrFail($id);
        $collection->user_id = auth()->id();
        
         if ($request->hasFile('image')) {
            try {
                $old = $collection->image ?: null;
                $collection->image = uploadImage($request->image, 'assets/collection/cover/', '320X350', $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Image could not be uploaded.'];
                return back()->withNotify($notify);
            }
        }
        $collection->title = $request->title;
        $collection->description = $request->description;
        $collection->update();
        $notify[]=['success','Collection updated successfully'];
        return back()->withNotify($notify);
    }

   
}