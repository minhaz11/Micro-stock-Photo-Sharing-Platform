<?php

namespace App\Http\Controllers\Admin;

use App\Image;
use App\Report;
use App\Reviewer;
use App\ApprovedPhoto;
use App\RejectedPhoto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewerController extends Controller
{
   

     //reviewers
     public function allReviewers()
     {
         $page_title = 'Manage Reviewers';
         $empty_message = 'No reviewer found';
         $users = Reviewer::latest()->paginate(getPaginate());
         return view('admin.reviewer.all', compact('page_title', 'empty_message', 'users'));
     }
 
     public function reviewerDetail($id)
     {
         $page_title = 'Reviewer Details';
         $user = Reviewer::findOrFail($id);
         $totalRejected = $user->rejects->count();
         $totalApproved = $user->approved->count();
         $totalReport = Report::whereReviewerId($user->id)->count();
 
         return view('admin.reviewer.details', compact('page_title', 'user','totalRejected','totalApproved','totalReport'));
     }
 
     public function reviewerUpdate(Request $request, $id)
     {
         $user = Reviewer::findOrFail($id);
       
         $request->validate([
             'firstname' => 'required|max:60',
             'lastname' => 'required|max:60',
             'email' => 'required|email|max:160|unique:reviewers,email,' . $user->id,
             'mobile' => 'required|unique:reviewers,mobile,' . $user->id,
         ]);
 
         $user->update([
             'mobile' => $request->mobile,
             'firstname' => $request->firstname,
             'lastname' => $request->lastname,
             'email' => $request->email,
             'address' => [
                 
                 'city' => $request->city,
                 'country' => $request->country,
             ],
             'status' => $request->status ? 1 : 0,
             'ev' => $request->ev ? 1 : 0,
             'sv' => $request->sv ? 1 : 0,
             
         ]);
 
         $notify[] = ['success', 'Reviewer detail has been updated'];
         return redirect()->back()->withNotify($notify);
     }
 
     public function reviewerLoginHistory($id)
     {
         $user = Reviewer::findOrFail($id);
         $page_title = 'Reviewer Login History - ' . $user->username;
         $empty_message = 'No reviewer login found.';
         $login_logs = $user->login_logs()->latest()->paginate(getPaginate());
         return view('admin.reviewer.logins', compact('page_title', 'empty_message', 'login_logs'));
     }
 
 
     public function reviewerShowEmailAllForm()
     {
         $page_title = 'Send Email To All Reviewers';
         return view('admin.reviewer.email_all', compact('page_title'));
     }
 
     public function reviewerSendEmailAll(Request $request)
     {
         $request->validate([
             'message' => 'required|string|max:65000',
             'subject' => 'required|string|max:190',
         ]);
        
         foreach (Reviewer::where('status', 1)->cursor() as $user) {
             send_general_email($user->email, $request->subject, $request->message, $user->username);
         }
 
         $notify[] = ['success', 'All Reviewer will receive an email shortly.'];
         return back()->withNotify($notify);
     }
 
 
     public function activeReviewers()
     {
         $page_title = 'Manage Active Reviewers';
         $empty_message = 'No active Reviewers found';
         $users = Reviewer::whereStatus(1)->latest()->paginate(getPaginate());
         return view('admin.reviewer.all', compact('page_title', 'empty_message', 'users'));
     }
 
     public function bannedReviewers()
     {
         $page_title = 'Banned Reviewers';
         $empty_message = 'No banned Reviewers found';
         $users = Reviewer::whereStatus(0)->latest()->paginate(getPaginate());
         return view('admin.reviewer.all', compact('page_title', 'empty_message', 'users'));
     }
 
     public function pendingReviewers()
     {
         $page_title = 'Pending Reviewers';
         $empty_message = 'No banned Reviewers found';
         $users = Reviewer::whereStatus(2)->latest()->paginate(getPaginate());
         return view('admin.reviewer.all', compact('page_title', 'empty_message', 'users'));
     }
 
     public function searchReviewer(Request $request, $scope)
     {
         $search = $request->search;
         $users = Reviewer::where(function ($user) use ($search) {
             $user->where('username', 'like', "%$search%")
                 ->orWhere('email', 'like', "%$search%")
                 ->orWhere('mobile', 'like', "%$search%")
                 ->orWhere('firstname', 'like', "%$search%")
                 ->orWhere('lastname', 'like', "%$search%");
         });
         $page_title = '';
         switch ($scope) {
             case 'active':
                 $page_title .= 'Active ';
                 $users = $users->where('status', 1);
                 break;
             case 'banned':
                 $page_title .= 'Banned';
                 $users = $users->where('status', 0);
                 break;
          
         }
         $users = $users->paginate(getPaginate());
         $page_title .= 'Reviewer Search - ' . $search;
         $empty_message = 'No search result found';
         return view('admin.reviewer.all', compact('page_title', 'search', 'scope', 'empty_message', 'users'));
     }
 


    public function approvedBy($id)
    {
        $user = Reviewer::findOrFail($id);
        $images = ApprovedPhoto::whereReviewerId($user->id)->with('image')->whereHas('image')->latest()->paginate(15);
        $page_title = "Approved by $user->username";
        $empty_message = "No data";
        return view('admin.photos.approvedBy',compact('images','page_title','empty_message'));
    }

    public function rejectedBy($id)
    {
        $user = Reviewer::findOrFail($id);
        $images = RejectedPhoto::whereReviewerId($user->id)->with('image')->whereHas('image')->latest()->paginate(15);
     
        $page_title = "Rejected by $user->username";
        $empty_message = "No data";
        return view('admin.photos.rejectedBy',compact('images','page_title','empty_message'));
    }

    public function reviewedBy($id)
    {
        $user = Reviewer::findOrFail($id);
        $reports = Report::whereReviewerId($user->id)->with('image')->whereHas('image')->get();
        $page_title = 'Reviewed by '.$user->username;
        $empty_message = 'No reviewed photos by you';
        $image = collect([]);
        $reports->map(function($item) use($image){
            $img = Image::findOrFail($item->image_id);
            $image->push($img);
        });
        $images= $image->paginate(15);
        return view('admin.photos.reviewedBy',compact('page_title','images','empty_message'));
    }

    public function showEmailSingleForm($id)
    {
        $user = Reviewer::findOrFail($id);
        $page_title = 'Send Email To: ' . $user->username;
        return view('admin.reviewer.email_single', compact('page_title', 'user'));
    }

    public function sendEmailSingle(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:65000',
            'subject' => 'required|string|max:190',
        ]);

        $user = Reviewer::findOrFail($id);
        send_general_email($user->email, $request->subject, $request->message, $user->username);
        $notify[] = ['success', $user->username . ' will receive an email shortly.'];
        return back()->withNotify($notify);
    }


}
