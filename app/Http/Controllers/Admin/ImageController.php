<?php

namespace App\Http\Controllers\Admin;

use App\Image;
use App\Report;
use App\Download;
use App\ApprovedPhoto;
use App\RejectedPhoto;
use App\GeneralSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ImageController extends Controller
{
    public function all(Request $request)
    {
        $search = $request->search;
        if($search){
            $page_title = "Search Result of $search";
            $images  = Image::where('title','like',"%$search%")->orWhereHas('user',function($user) use($search){
                $user->where('username',$search);
            })->with(['category','user'])->paginate(15);
        } else{

            $page_title = "All Photos";
            $images  = Image::latest()->with(['category','user'])->paginate(15);
        }
        $empty_message = "No photos found";
        return view('admin.photos.all',compact('page_title','empty_message','images','search'));
    }
    public function premiumAll(Request $request)
    {
        $search = $request->search;
        if($search){
            $page_title = "Search Result of $search";
            $images  = Image::where('premium',1)->where('title','like',"%$search%")->orWhereHas('user',function($user) use($search){
                $user->where('username',$search);
            })->with(['category','user'])->paginate(15);
        } else{

            $page_title = "All Premium Photos";
            $images  = Image::where('premium',1)->latest()->with(['category','user'])->paginate(15);
        }
        $empty_message = "No photos found";
        return view('admin.photos.all',compact('page_title','empty_message','images','search'));
    }
    public function freeAll(Request $request)
    {
        $search = $request->search;
        if($search){
            $page_title = "Search Result of $search";
            $images  = Image::where('premium',0)->where('title','like',"%$search%")->orWhereHas('user',function($user) use($search){
                $user->where('username',$search);
            })->with(['category','user'])->paginate(15);
        } else{

            $page_title = "All Free Photos";
            $images  = Image::where('premium',0)->latest()->with(['category','user'])->paginate(15);
        }
        $empty_message = "No photos found";
        return view('admin.photos.all',compact('page_title','empty_message','images','search'));
    }

    public function pendingReviews()
    {
       
        $page_title = "Pending Reviews";
        $pendings  = Image::whereStatus(0)->whereNotNull('reviewing_status')->where('reviewing_status->admin_id',auth()->guard('admin')->id())->with(['category','user'])->paginate(15);
        
        $empty_message = "No data found";
        return view('admin.photos.pending',compact('page_title','empty_message','pendings'));
    }
    

    public function review($id)
    {
        $image = Image::find($id);
        if(!$image){
            $notify[]=['error','Image Not Found'];
            return back()->withNotify($notify);
        }
      
        if($image->reviewing_status == null){
            $image->reviewing_status = [
                'admin_id' => auth()->guard('admin')->id()
            ];
            $image->update();
        }
        $page_title = "Image Details";
        $empty_message = "No data";
        return view('admin.photos.details',compact('image','page_title','empty_message'));
    }
    public function details($id)
    {
        $image = Image::find($id);
        if(!$image){
            $notify[]=['error','Image Not Found'];
            return back()->withNotify($notify);
        }
     
        $page_title = "Image Details";
        $empty_message = "No data";
        return view('admin.photos.details',compact('image','page_title','empty_message'));
    }

    public function pending(Request $request)
    {
        $search = $request->search;
        if($search){
            $page_title = "Search Result of $search";
            $pendings  = Image::where('status',0)->where('title','like',"%$search%")->orWhereHas('user',function($user) use($search){
                $user->where('username',$search);
            })->with(['category','user'])->paginate(15);
        } else {
            $page_title = "Pending Photos";
            $pendings = Image::where('status',0)->with(['category','user'])->paginate(15);
        }
        $empty_message = "No pending photos";

        return view('admin.photos.pending',compact('page_title','empty_message','pendings','search'));
    }


    public function rejectPhoto(Request $request)
    {
        $image = Image::find($request->image_id);
        if(!$image){
            $notify[]=['error','Sorry image not found'];
            return back()->withNotify($notify);
        }
        if($image->status == 3){
            $notify[]=['error','Sorry image is already rejected'];
            return back()->withNotify($notify);
        }
        $image->status = 3;
        $image->update();
        
        $rejectPhoto = RejectedPhoto::firstOrNew(['image_id'=>$request->image_id,'user_id'=>$request->user_id]);
        $rejectPhoto->admin_id = auth()->guard('admin')->id();
        $rejectPhoto->category_id = $image->category->id;
        $rejectPhoto->reasons = $request->reason?? null;
        $rejectPhoto->description =  $request->details ?? null;
        $rejectPhoto->save();
        
        $reject_msg =  str_replace(' on','',implode('<br> ',$request->reason)).'<br>'.$request->details;
        notify($image->user,'PHOTO_REJECT',[
            'title' => $image->title,
            'category' => $image->category->name,
            'reject_message' =>  $reject_msg
        ]);

        $notify[]=['info','Photo has been rejected'];
        return redirect()->back()->withNotify($notify);
    }

    public function approvePhoto($id)
    {
        $image = Image::find($id);
        if(!$image){
            $notify[]=['error','Sorry image not found'];
            return back()->withNotify($notify);
        }
        if($image->status == 1){
            $notify[]=['error','Sorry image is already approved'];
            return back()->withNotify($notify);
        }
        $image->status = 1;
        $image->updated = 0;
        $image->update();

       
        $approve = ApprovedPhoto::firstOrNew([
            'image_id' => $id,
        ]);
        $approve->user_id=  $image->user->id;
        $approve->category_id = $image->category->id;
        $approve->admin_id = auth()->guard('admin')->id();
        $approve->reviewer_id = null;
        $approve->save();

        notify($image->user,'PHOTO_APPROVE',[
            'title' => $image->title,
            'category' => $image->category->name
        ]);
       
        RejectedPhoto::where('image_id',$id)->delete();

        $notify[]=['success','Photo has been approved'];
        return back()->withNotify($notify);

    }

    public function approved(Request $request)
    {
        $search = $request->search;
        if($search){
            $page_title = "Search Result of $search";
            $images  = Image::where('status',1)->where('title','like',"%$search%")->orWhereHas('user',function($user) use($search){
                $user->where('username',$search);
            })->with(['category','user'])->paginate(15);
        } else {
            $page_title = "Approved Photos";
            $images = Image::where('status',1)->latest()->with(['category','user'])->paginate(15);
        }
        $empty_message = "No Photos Found";
        return view('admin.photos.approved',compact('images','page_title','empty_message','search'));
    }

    public function rejected(Request $request)
    {
        $search = $request->search;
        if($search){
            $page_title = "Search Result of $search";
            $images  = Image::where('status',3)->where('title','like',"%$search%")->orWhereHas('user',function($user) use($search){
                $user->where('username',$search);
            })->with(['category','user'])->paginate(15);
        } else {
            $page_title = "Rejected Photos";
            $images = Image::where('status',3)->latest()->with(['category','user'])->paginate(15);
        }
        $empty_message = "No Rejected Photos";
        return view('admin.photos.rejected',compact('images','page_title','empty_message','search'));
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

    public function downloads()
    {
        $page_title = 'Photo download logs';
        $empty_message = 'No Data';
        $downloads = Download::latest()->with('image')->whereHas('image')->paginate(15);
        return view('admin.photos.downloads',compact('page_title','empty_message','downloads'));
    }

    public function downloadsByIp($ip)
    {
        $page_title = 'Photo Download Logs By Ip';
        $downloads = Download::where('ip',$ip)->latest()->with('image')->whereHas('image')->paginate(15);
        $empty_message = 'No Data';
        return view('admin.photos.downloads',compact('page_title','empty_message','downloads'));
    }

    public function pendingReport()
    {
        $pendingReports = Report::whereStatus(0)->with('image')->whereHas('image')->groupBy('image_id')->paginate(15);
        $page_title = "Pending Photo Reports";
        $empty_message = "No data";
        return view('admin.photoreport.pending',compact('pendingReports','page_title','empty_message'));
    }

    public function reportAction($id)
    {
        $page_title='Review Report';
        $image = Image::find($id);
        if(!$image){
            $notify[]=['error','Image Not Found'];
            return back()->withNotify($notify);
        }

        $report = Report::whereImageId($id)->first();
        $admin = auth()->guard('admin')->user();
        if($report->reviewer_id ==  null){
            Report::whereImageId($image->id)->update(['admin_id'=>auth()->guard('admin')->id()]);
        }
            
       
        return view('admin.photoreport.reviewPage',compact('page_title','image'));
    }

    public function pendingReportReviews()
    {
        $pendingReports = Report::where('admin_id',auth()->guard('admin')->id())->whereStatus(0)->with('image')->whereHas('image')->groupBy('image_id')->paginate(15);
        $page_title = "Pending Report Reviews";
        $empty_message = "No data";
        return view('admin.photoreport.pending',compact('pendingReports','page_title','empty_message')); 
    }

    public function reportRemove($id)
    {
        $reports = Report::whereImageId($id)->get();
        if($reports->isEmpty()){
            $notify[]=['error','Sorry Reports are all ready removed'];
            return back()->withNotify($notify);
        }
        $reports->delete();
        $notify[]=['success','All reports of this photo removed successfully'];
        return back()->withNotify($notify);

    }
    public function removeReport($id)
    {
        $report = Report::find($id);
        if(!$report){
            $notify[]=['error','Sorry report already been removed'];
            return back()->withNotify($notify);
        }
        $report->delete();
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

    
    
    

}
