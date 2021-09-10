<?php

namespace App\Http\Controllers\Reviewer;

use App\Image;
use App\Report;
use Carbon\Carbon;
use App\ApprovedPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Reasons;
use App\RejectedPhoto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ReviewerController extends Controller
{
    public function dashboard()
    {
        
        $user = auth()->guard('reviewer')->user();
        $page_title = 'Reviewer Dashboard';
        $data['totalApproved'] = auth()->guard('reviewer')->user()->approved->count();
        $data['totalRejected'] = auth()->guard('reviewer')->user()->rejects->count();
        $data['totalReportReview'] = Report::whereReviewerId(auth()->guard('reviewer')->id())->count();

        $reports = Report::whereReviewerId(auth()->guard('reviewer')->id())->get();
        $image = collect([]);
        $reports->map(function($item) use($image){
            $img = Image::find($item->image_id);
            if($img && $img->status == 2){
               $image->push($img);
            }
        });
        $data['totalBanned']= $image->count();
        $data['totalPending'] = Image::whereStatus(0)->count();
        $data['totalPendingReport'] = Report::whereStatus(0)->count();


        //chart data
        $report['app_months'] = collect([]);
        $report['rej_months'] = collect([]);
        $report['approved'] = collect([]);
        $report['rejected'] = collect([]);

  

        $approvedPhoto = ApprovedPhoto::whereReviewerId($user->id)->whereYear('created_at', '>=', Carbon::now()->subYear())
            ->selectRaw("SUM( CASE WHEN reviewer_id = $user->id THEN id END) as total")
            ->selectRaw("DATE_FORMAT(created_at,'%M') as months")
            ->orderBy('created_at')
            ->groupBy(DB::Raw("MONTH(created_at)"))->get();
            

        $approvedPhoto->map(function ($item) use ($report) {
            $report['app_months']->push($item->months);
            $report['approved']->push($item->total);
        });


        $rejectedPhoto = RejectedPhoto::whereReviewerId($user->id)->whereYear('created_at', '>=', Carbon::now()->subYear())
        ->selectRaw("SUM( CASE WHEN reviewer_id = $user->id THEN id END) as total")
        ->selectRaw("DATE_FORMAT(created_at,'%M') as months")
        ->orderBy('created_at')
        ->groupBy(DB::Raw("MONTH(created_at)"))->get();

        $rejectedPhoto->map(function ($item) use ($report) {
            $report['rej_months']->push($item->months);
            $report['rejected']->push($item->total);
        });
      
        return view('reviewer.dashboard',compact('page_title','data','report'));
    }

    public function profile()
    {
        $page_title = 'Profile';
        $reviewer = Auth::guard('reviewer')->user();
        return view('reviewer.profile', compact('page_title', 'reviewer'));
    }

    public function profileUpdate(Request $request)
    {
        $this->validate($request, [
            'fname' => 'required',
            'lname' => 'required',
            'country' => 'required',
            'city' => 'required',
            'email' => 'required|email',
            'image' => 'nullable|image|mimes:jpg,jpeg,png'
        ]);

        $user = Auth::guard('reviewer')->user();

        if ($request->hasFile('image')) {
            try {
                $old = $user->image ?: null;
                $user->image = uploadImage($request->image, 'assets/reviewer/images/profile/', '400X400', $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Image could not be uploaded.'];
                return back()->withNotify($notify);
            }
        }

        $user->firstname = $request->fname;
        $user->lastname = $request->lname;
        $user->email = $request->email;
        $user->address =[
            'country'=>$request->country,
            'city'=>$request->city
        ];
       
        $user->save();
        $notify[] = ['success', 'Your profile has been updated.'];
        return redirect()->route('reviewer.profile')->withNotify($notify);
    }


    public function password()
    {
        $page_title = 'Password Setting';
        $reviewer = Auth::guard('reviewer')->user();
        return view('reviewer.password', compact('page_title', 'reviewer'));
    }

    public function passwordUpdate(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required|min:5|confirmed',
        ]);

        $user = Auth::guard('reviewer')->user();
        if (!Hash::check($request->old_password, $user->password)) {
            $notify[] = ['error', 'Password Do not match !!'];
            return back()->withErrors(['Invalid old password.']);
        }
        $user->update([
            'password' => bcrypt($request->password)
        ]);
        $notify[] = ['success', 'Password Changed Successfully.'];
        return redirect()->route('reviewer.password')->withNotify($notify);
    }

    public function reasonAll()
    {
        $page_title = "Predefined reasons";
        $empty_message = "No predefined reasons";

        $reasons = Reasons::latest()->paginate(15);
        return view('reviewer.reasons.all',compact('page_title','empty_message','reasons'));

    }

    public function reasonBy()
    {
        $page_title = "Predefined reasons";
        $empty_message = "No predefined reasons";

        $reasons = Reasons::whereReviewerId(auth()->guard('reviewer')->id())->latest()->paginate(15);
        return view('reviewer.reasons.byReviewer',compact('page_title','empty_message','reasons'));
    }

    public function reasonAdd(Request $request)
    {
       

        $request->validate([
            'reason' => 'required'
        ]);
        $data['reviewer_id']=auth()->guard('reviewer')->id();
        $data['reason'] = $request->reason;
        Reasons::create($data);
        if($request->wantsJson()){
            return response()->json(['success' => 'This reason has added to your list']);
        }
        $notify[]=['success','New reason added successfully'];
        return back()->withNotify($notify);
    }

    public function reasonUpdate(Request $request,$id)
    {
        $request->validate([
            'reason' => 'required'
        ]);
        $reason = Reasons::findOrFail($id);
        $reason->reason = $request->reason;
        $reason->update();
        $notify[]=['success','Reason updated successfully'];
        return back()->withNotify($notify);

    }

    public function reasonRemove($id)
    {
        Reasons::findOrFail($id)->delete();
        $notify[]=['success','Reason Deleted Successfully'];
        return back()->withNotify($notify);
    }

}
