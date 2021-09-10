<?php

namespace App\Http\Controllers\User;

use App\Collection;
use App\CollectionImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CollectionController extends Controller
{
    public function all()
    {
        $page_title = 'Collections';
        $empty_message = "No data";
        $collections = Collection::whereUserId(auth()->id())->latest()->paginate(12);
        return view(activeTemplate().'user.collection.all',compact('page_title','collections','empty_message'));
    }

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

    public function collectionRemove($id)
    {
        $collection = Collection::findOrFail($id);
        CollectionImage::whereCollectionId($collection->id)->delete();
        $collection->delete();

        $notify[]=['success','Collection Removed successfully'];
        return redirect(route('user.collection.all'))->withNotify($notify);

    }

    public function collectionImages($id)
    {
        $collection = Collection::with('images')->findOrFail($id);
        $page_title = 'Images of this collection';
        $empty_message = 'No images to this collection';
        return view(activeTemplate().'user.collection.images',compact('collection','empty_message','page_title'));
    }

    public function collectionSearch(Request $request)
    {
        $value = $request->search;
        $collections = Collection::whereUserId(auth()->id())->where('title','like',"%$value%")->paginate(12);
        $page_title = "Results of $value";
        $empty_message = "No collections";
        return view(activeTemplate().'user.collection.all',compact('page_title','collections','empty_message','value'));

    }

    public function collectionImageRemove(Request $request)
    {
        
        CollectionImage::whereCollectionId($request->collId)->whereImageId($request->imageId)->delete();
        return response()->json(['success' => 'Image deleted from this collection', 'data'=>$request->collId]);
    }

 
}
