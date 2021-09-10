<?php

namespace App\Http\Controllers\Reviewer;

use App\Image;
use App\ApprovedPhoto;
use App\RejectedPhoto;
use App\GeneralSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PhotoReviewController extends Controller
{
    public function pending()
    {
        $page_title = "Pending Photos";
        $empty_message = "No data";
        $pendings = Image::whereStatus(0)->with(['category','user'])->paginate(15);
        return view('reviewer.photo.pending',compact('page_title','empty_message','pendings'));
    }

    public function pendingReviews()
    {
        $page_title = "Pending Reviews";
        $empty_message = "No data";
        $pendings = Image::whereStatus(0)->whereNotNull('reviewing_status')->where('reviewing_status->reviewer_id',auth()->guard('reviewer')->id())->with(['category','user'])->paginate(15);

        return view('reviewer.photo.pending',compact('page_title','empty_message','pendings'));
    }
    

    public function action($id)
    {
        $page_title='Review Photo';
        $image = Image::find($id);
        if(!$image){
            $notify[]=['error','Image Not Found'];
            return back()->withNotify($notify);
        }

        $reviewer = auth()->guard('reviewer')->user();
        if($image->reviewing_status != null && @$image->reviewing_status->reviewer_id != $reviewer->id){
            $notify[]=['error','Sorry this photo is under reviewing by reviewer or admin'];
            return back()->withNotify($notify);
        }

        if($image->reviewing_status == null){

            $image->reviewing_status = [
                'reviewer_id' => auth()->guard('reviewer')->id()
            ];
            $image->update();
        }

        return view('reviewer.photo.reviewPage',compact('page_title','image'));
    } 

    public function rejectPhoto(Request $request)
    {
        $image = Image::find($request->image_id);
        if(!$image){
            $notify[]=['error','Image Not Found'];
            return back()->withNotify($notify);
        }
        if($image->status == 3){
            $notify[]=['error','This image is already rejected'];
            return back()->withNotify($notify);
        }
        $image->status = 3;
        $image->update();
        
        $rejectPhoto = RejectedPhoto::firstOrNew(['image_id'=>$request->image_id,'user_id'=>$request->user_id]);
        $rejectPhoto->reviewer_id = auth()->guard('reviewer')->id();
        $rejectPhoto->admin_id = null;
        $rejectPhoto->category_id = $image->category->id;
        $rejectPhoto->reasons = $request->reason ?? null;
        $rejectPhoto->description =  $request->details ?? null;
        $rejectPhoto->save();

        $reject_msg =  str_replace(' on','',implode('<br> ',$request->reason)).'<br>'.$request->details;
        notify($image->user,'PHOTO_REJECT',[
            'title' => $image->title,
            'category' => $image->category->name,
            'reject_message' =>  $reject_msg
        ]);

        $notify[]=['info','Photo has been rejected'];
        return redirect()->route('reviewer.photo.pending')->withNotify($notify);
    }

    public function approvePhoto($id)
    {
        $image = Image::find($id);
        if(!$image){
            $notify[]=['error','Image Not Found'];
            return back()->withNotify($notify);
        }
        if($image->status == 1){
            $notify[]=['error','This image is already approved'];
            return back()->withNotify($notify);
        }

        $image->status = 1;
        $image->updated = 0; 
        $image->update();

        $approve  = new ApprovedPhoto();
        $approve->image_id = $image->id;
        $approve->user_id = $image->user->id;
        $approve->category_id = $image->category->id;
        $approve->reviewer_id = auth()->guard('reviewer')->id();
        $approve->save();

        notify($image->user,'PHOTO_APPROVE',[
            'title' => $image->title,
            'category' => $image->category->name
        ]);


        $notify[]=['success','Photo has been approved'];
        return redirect()->route('reviewer.photo.pending')->withNotify($notify);
    }

    public function approved()
    {
        $images = Image::where('status',1)->latest()->paginate(15);
        $page_title = "Approved Images";
        $empty_message = "No data";
        return view('reviewer.photo.approved',compact('images','page_title','empty_message'));
    }

    public function approvedBy()
    {
        $images = ApprovedPhoto::whereHas('reviewer')->whereReviewerId(auth()->guard('reviewer')->id())->with('image')->whereHas('image')->latest()->paginate(15);
        $page_title = "Approved by me";
        $empty_message = "No data";
        return view('reviewer.photo.approvedBy',compact('images','page_title','empty_message'));
    }

    public function rejectedBy()
    {
        $images = RejectedPhoto::whereHas('reviewer')->whereReviewerId(auth()->guard('reviewer')->id())->with('image')->whereHas('image')->latest()->paginate(15);
     
        $page_title = "Rejected by me";
        $empty_message = "No data";
        return view('reviewer.photo.rejectedBy',compact('images','page_title','empty_message'));
    }

    public function downloadFile($id)
    {

        $general = GeneralSetting::first();
        $image = Image::findOrFail($id);

        if($general->select_storage == 1){
            $filepath = 'assets/contributor/files/'.$image->file;
            $extension = getExtension($filepath);
            $fileName =  $general->sitename.'_'.$image->track_id.'.'.$extension;
            $headers = [
                'Content-Type' => 'application/octet-stream', 
                'Cache-Control' => 'no-store, no-cache'
            ];
            file_get_contents($filepath);
            $this->downloadData($image->id,$image->resolution, $extension);
            return response()->download( $filepath, $fileName, $headers);
        } else {
            $filepath = fileUrl($image->file);
            $extension = getExtension($filepath);
            $fileName =  $general->sitename.'_'.$image->track_id.'.'.$extension;
            header('Content-type: application/octet-stream');
            header("Content-Disposition: attachment; filename=".$fileName);
            while (ob_get_level()) {
                ob_end_clean();
            }
            readfile($filepath);
        }

    }
}
