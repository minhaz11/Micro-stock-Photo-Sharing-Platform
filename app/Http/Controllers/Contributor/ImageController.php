<?php

namespace App\Http\Controllers\Contributor;

use App\Image;
use App\Category;
use App\ApprovedPhoto;
use App\RejectedPhoto;
use App\GeneralSetting;
use App\CollectionImage;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{


    public function pending()
    {
        $images = Image::whereUserId(Auth::id())->whereStatus(0)->latest()->paginate(15);
        $page_title = "Pending Photos";
        $empty_message = "No data";
        $scope = 'pending';
        return view(activeTemplate().'contributor.image.all',compact('images','page_title','empty_message','scope'));
    }

    public function approvedPhotos()
    {
        $images = Image::whereUserId(Auth::id())->whereStatus(1)->latest()->paginate(15);
        $page_title = "Approved Photos";
        $empty_message = "No data";
        $scope = 'approved';
        return view(activeTemplate().'contributor.image.all',compact('images','page_title','empty_message','scope'));
    }
    public function rejectedPhotos()
    {
        $images = Image::whereUserId(Auth::id())->whereStatus(3)->latest()->paginate(15);
        $page_title = "Rejected Photos";
        $empty_message = "No data";
        $scope = 'rejected';
        return view(activeTemplate().'contributor.image.all',compact('images','page_title','empty_message','scope'));
    }
    public function bannedPhotos()
    {
        $images = Image::whereUserId(Auth::id())->whereStatus(2)->latest()->paginate(15);
        $page_title = "Banned Photos";
        $empty_message = "No data";
        $scope = 'banned';
        return view(activeTemplate().'contributor.image.all',compact('images','page_title','empty_message','scope'));
    }

    public function add()
    {
        $page_title = "Upload Image";
        $gnl = GeneralSetting::first(['ins_file','instruction']);

        $empty_message = "No data";
        $categories = Category::get(['id','name']);
        return view(activeTemplate().'contributor.image.upload',compact('page_title','categories','gnl'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'title'=> 'required',
            'tags.*'=>'required',
            'category' => 'required',
            'value'=>'required',
            'file' => 'required|mimes:zip',
            'description' => 'required|min:100|max:191',
            'photo' => 'image|dimensions:min_width=1024,min_height:768|max:7120'
        ],
        [
            'photo.dimensions'=>'Required minimum width 1024x768px resolution image',

        ]
    );
        $gnl = GeneralSetting::first(['select_storage','ftp']);
        $tagCount =  count($request->tags);
        if($tagCount>11){
            $notify[] = ['error', 'you can not use more than 10 tags'];
            return back()->withNotify($notify);
        }

        $image = new Image();
        if ($request->hasFile('photo')) {
            try {
                $image->image_name = watermark($request->photo)[0];
                $image->image_thumb = watermark($request->photo)[1];
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Image could not be uploaded.'];
                return back()->withNotify($notify);
            }        
         
        }
        if ($request->hasFile('file')) {
            $directory = date("Y")."/".date("m")."/".date("d");
            $location = 'assets/contributor/files/'.$directory;
            ftpConfig($gnl);
            if($gnl->select_storage == 2){
                $driver = Storage::disk('custom-ftp');
            }
            if($gnl->select_storage == 3){
                $driver = Storage::disk('s3');
            }
            $filename = time() . '.' . $request->file->extension();

            if($gnl->select_storage == 1){
                makeDirectory($location);
                $request->file->move($location,$filename);
            } else {
                makeDirectoryFtp($location,$driver);
                $driver->put($location.'/'.$filename, fopen($request->file('file'), 'r+'));
            }
            $image->file = $directory.'/'.$filename;
        }

        list($width, $height) = getimagesize($request->photo);
        $image->extension = $request->photo->extension();
        $image->width = $width;
        $image->height = $height;
        $image->resolution = $width.'x'.$height;
        $image->title = $request->title;
        $image->tags = $request->tags;
        $image->category_id = $request->category;
        $image->user_id = Auth::id();
        $image->date = Carbon::now();
        $image->status = 0;
        $image->track_id = rand(10000000, 999999999);
        $image->premium = $request->value;
        $request->value == 1 ? $image->attribution = 1: $image->attribution = 0;
        $image->description = $request->description;
        $image->save();

        $notify[]=['success',trans('Image uploaded successfully')];
        return back()->withNotify($notify);

    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'title'=> 'required',
            'tags.*'=>'required',
            'category' => 'required',
            'value'=>'required',
            'file' => 'required_if:form_file,1|mimes:zip',

            'description' => 'required|min:100|max:191',
            'photo' => 'image|dimensions:min_width=1024,min_height=768|max:7120'
        ],
        [
            'photo.dimensions'=>'Required minimum 1024x768px resolution image',

        ]
    );
       $gnl = GeneralSetting::first(['select_storage','ftp']);
        $tagCount =  count($request->tags);
        if($tagCount>11){
            $notify[] = ['error', 'you can not use more than 10 tags'];
            return back()->withNotify($notify);
        }

        $image = Image::findOrFail($id);

        if ($request->hasFile('photo')) {
            try {
            $old =  $image->image_name ?? null;
            $oldThumb =  $image->image_thumb ?? null;
            $imageData = watermark($request->photo,$old,$oldThumb);
            
            $image->image_name = $imageData[0];
            $image->image_thumb = $imageData[1];

            } catch (\Exception $exp) {
                $notify[] = ['error', 'Image could not be uploaded.'];
                return back()->withNotify($notify);
            }
            list($width, $height) = getimagesize($request->photo);
            $image->extension = $request->photo->extension();
            $image->width = $width;
            $image->height = $height;
            $image->resolution = $width.'x'.$height;

        }
        if ($request->hasFile('file')) {
            $directory = date("Y")."/".date("m")."/".date("d");
            $location = 'assets/contributor/files/'.$directory;
            ftpConfig($gnl);
            if($gnl->select_storage == 2){
                $driver = Storage::disk('custom-ftp');
            }
            if($gnl->select_storage == 3){
                $driver = Storage::disk('s3');
            }
            $filename = time() . '.' . $request->file->extension();
            if ($image->file!=null) {
                if(!removeFile('assets/contributor/files/'. $image->file)){
                    removeFtpFile($image->file,$driver);
                }
            }

            $filename = time() . '.' . $request->file->extension();
            if($gnl->select_storage == 1){
                makeDirectory($location);
                $request->file->move($location,$filename);
            } else {
                makeDirectoryFtp($location,$driver);
                $driver->put($location.'/'.$filename, fopen($request->file('file'), 'r+'));
            }
            $image->file = $directory.'/'.$filename;
        }

        $image->title = $request->title;
        $image->tags = $request->tags;
        $image->category_id = $request->category;
        $image->updated = 1;
        $image->user_id = Auth::id();
        $image->date = Carbon::now();
        $image->status = 0;
        $image->premium = $request->value;
        $request->value == 1 ? $image->attribution = 1: $image->attribution = 0;
        $image->description = $request->description;
        $image->update();

        $notify[]=['success','Image updated successfully'];
        return back()->withNotify($notify);
    }

    public function edit($id)
    {
        $image = Image::findOrFail($id);
        if($image->user_id != auth()->id()){
            $notify[]=['error','Sorry image couldn\'t found'];
            return back()->withNotify($notify);
        }
        $page_title = 'Update Image Details';
        $gnl = GeneralSetting::first(['ins_file','instruction']);
        $categories = Category::all();
        return view(activeTemplate().'contributor.image.update',compact('image','page_title','categories','gnl'));
    }

    public function remove($id)
    {
        $image = Image::findOrFail($id);
        if($image->user_id != auth()->id()){
            $notify[]=['error','Sorry image couldn\'t found'];
            return back()->withNotify($notify);
        }
        $collect = CollectionImage::whereImageId($image->id)->first();
        if($collect!=null){
            $collect->delete();
        }

        $rejected =  RejectedPhoto::whereImageId($image->id)->first();
        if( $rejected!=null){
            $rejected->delete();
        }
        $approved = ApprovedPhoto::whereImageId($image->id)->first();
        if($approved!=null){
            $approved->delete();
        }

        removeFile('assets/contributor/file/'.$image->file);
        removeFile('assets/contributor/thumb/'.$image->image_name);
        removeFile('assets/contributor/watermark/'.$image->image_name);
        $image->delete();
        $notify[]=['success','Image removed successfully'];
        return back()->withNotify($notify);
    }

    public function searchImage(Request $request,$scope)
    {
        $search = $request->search;
        $images = Image::whereUserId(auth()->id())->where(function ($q) use ($search) {
            $q->where('title', 'like', "%$search%")
                ->orWhere('tags', 'like', "%$search%");
        });
        $page_title = '';
        switch ($scope) {
            case 'pending':
                $page_title .= 'pending ';
                $images = $images->where('status', 0);
                break;
            case 'banned':
                $page_title .= 'Banned ';
                $images = $images->where('status', 2);
                break;
            case 'approved':
                $page_title .= 'Approved ';
                $images = $images->whereStatus(1);
                break;
            case 'rejected':
                $page_title .= 'rejected ';
                $images = $images->where('status', 3);
                break;
        }
        $images = $images->paginate(getPaginate());
        $page_title .= 'Images Search - ' . $search;
        $empty_message = 'No search result found';
        return view(activeTemplate().'contributor.image.all', compact('page_title', 'search', 'scope', 'empty_message', 'images'));
    }
}
