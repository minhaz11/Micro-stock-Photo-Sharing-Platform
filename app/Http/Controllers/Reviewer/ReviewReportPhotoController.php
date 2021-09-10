<?php

namespace App\Http\Controllers\Reviewer;

use App\Image;
use App\Report;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewReportPhotoController extends Controller
{
    public function pending()
    {
        $pendingReports = Report::whereStatus(0)->with('image')->whereHas('image')->groupBy('image_id')->paginate(15);
        $page_title = "Pending Photo Reports";
        $empty_message = "No data";
        return view('reviewer.photoreport.pending',compact('pendingReports','page_title','empty_message'));
    }

    public function pendingReportReviews()
    {
        $pendingReports = Report::where('reviewer_id',auth()->guard('reviewer')->id())->whereStatus(0)->with('image')->whereHas('image')->groupBy('image_id')->paginate(15);
        $page_title = "Pending Report Reviews";
        $empty_message = "No data";
        return view('reviewer.photoreport.pending',compact('pendingReports','page_title','empty_message')); 
    }
    

    public function action($id)
    {
        $page_title='Review Report';
        $image = Image::find($id);
        if(!$image){
            $notify[]=['error','Image Not Found'];
            return back()->withNotify($notify);
        }

        $report = Report::whereImageId($id)->first();
        $reviewer = auth()->guard('reviewer')->user();
        if($report->reviewer_id != null && $report->reviewer_id !=  $reviewer->id || $report->admin_id != null){
             $notify[]=['error','Sorry someone is reviewing this Photo'];
             return back()->withNotify($notify);
        }
            
        Report::whereImageId($image->id)->update(['reviewer_id'=>auth()->guard('reviewer')->id()]);
        return view('reviewer.photoreport.reviewPage',compact('page_title','image'));
    } 

    public function reportRemove($id)
    {
        $report = Report::find($id);
        if(!$report){
            $notify[]=['error','Sorry report already been removed'];
            return back()->withNotify($notify);
        }
        $notify[]=['success','All reports of this photo removed successfully'];
        return back()->withNotify($notify);

    }
    public function removeReport($id)
    {
        $reports = Report::whereImageId($id)->get();
        if($reports->isEmpty()){
            $notify[]=['error','Sorry Reports are all ready removed'];
            return back()->withNotify($notify);
        }
        $reports->delete();
        $notify[]=['success','Report removed successfully'];
        return back()->withNotify($notify);
    }

    public function banned($id)
    {
        $image = Image::find($id);
        if(!$image){
            $notify[]=['error','Sorry image not found'];
            return back()->withNotify($notify);
        }
        if($image->status == 2){
            $notify[]=['error','Sorry image is already been banned'];
            return back()->withNotify($notify);
        }
        $image->status = 2;
        $image->update();

        notify($image->user,'PHOTO_BANNED',[
            'title' => $image->title,
            'category' => $image->category->name
        ]);

        Report::whereImageId($image->id)->update(['status' => 1]);
        $notify[]=['success','Photo banned successfully'];
        return back()->withNotify($notify);
    }

    public function bannedBy()
    {
        $reports = Report::whereHas('reviewer')->whereReviewerId(auth()->guard('reviewer')->id())->with('image')->whereHas('image')->get();
        $page_title = 'Banned by me';
        $empty_message = 'No banned photos by you';
        $image = collect([]);
        $reports->map(function($item) use($image){
            $img = Image::find($item->image_id);
            if(!$img){
                $notify[]=['error','Sorry Image not found'];
                return back()->withNotify($notify);
            }
            if($img->status==2){
               $image->push($img);
            }
        });
        $images= $image->paginate(15);
        return view('reviewer.photoreport.bannedBy',compact('page_title','images','empty_message'));
       
    }

    public function reviewedBy()
    {
        $reports = Report::whereHas('reviewer')->whereReviewerId(auth()->guard('reviewer')->id())->with('image')->whereHas('image')->get();
        $page_title = 'Reviewed by me';
        $empty_message = 'No reviewed photos by you';
        $image = collect([]);
        $reports->map(function($item) use($image){
            $img = Image::find($item->image_id);
            if(!$img){
                $notify[]=['error','Sorry Image not found'];
                return back()->withNotify($notify);
            }
            $image->push($img);
        });
        $images= $image->paginate(15);
        return view('reviewer.photoreport.reviewedBy',compact('page_title','images','empty_message'));
    }


}
