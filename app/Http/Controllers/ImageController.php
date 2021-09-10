<?php

namespace App\Http\Controllers;

use App\Like;
use App\User;
use App\Badge;
use App\Image;
use App\Views;
use App\Follow;
use App\Report;
use App\Category;
use App\Download;
use App\UserBadge;
use Carbon\Carbon;
use App\Collection;
use App\Transaction;
use App\GeneralSetting;
use App\CollectionImage;
use App\EarningLog;
use App\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ImageController extends Controller
{
  public function imageDetails($id)
    {
        $page_title = "Image Details";
       
        $image = Image::with(['collections','likes','downloads','comments','views','user','category'])->find($id);
        if($image==null){
            $notify[]=['error','Sorry image not found'];
            return back()->withNotify($notify);
        }
        $images = Image::whereStatus(1)->whereCategoryId($image->category_id)->with(['likes','downloads','user'])->get();
        $user = auth()->user();
        $views = Views::whereIp(request()->ip())->whereImageId($id)->first();
        
        if($views == null){
            Views::create([
                'image_id'=> $image->id,
                'ip' => request()->ip(),
                'date' => Carbon::now()
            ]);
        }
        session()->put('prev_url',url()->current());
        return view(activeTemplate().'frontend.imageDetails',compact('image','user','page_title','images'));
    }

    //save download data
    public function downloadData($imageId,$resolution,$type)
    {
        $download = null;
        $general = GeneralSetting::first();
        if(Auth::check()){
            $download = Download::firstOrNew(['image_id'=>$imageId,'user_id'=>auth()->id(),'ip'=>request()->ip()]);
        } else {
           
            $download = Download::firstOrNew(['image_id'=>$imageId,'user_id'=>null,'ip'=>request()->ip()]);
        }
       
        $download->date = Carbon::now();
        $download->resolution =  $resolution;
        $download->type =  $type;
        
        $image = Image::findOrFail($imageId);
        $download->contributor_id =  $image->user->id;
        $image->premium == 1 ? $download->premium = 1 : $download->premium = null; 
       
        if($image->user->id != auth()->id()){ 
           $count = Download::whereImageId($imageId)->whereUserId(auth()->id())->count();
            if($image->premium == 1 && $count < 1){
            
                $image->user->balance += $general->per_download;
                $image->user->update();

                $earn = new EarningLog();
                $earn->contributor_id =  $image->user->id;
                $earn->image_id = $image->id;
                $earn->amount = $general->per_download;
                $earn->date = Carbon::now()->toDateString();
                $earn->save();
  
                $transaction = new Transaction();
                $transaction->user_id = $image->user->id;
                $transaction->amount = $general->per_download;
                $transaction->post_balance = getAmount($image->user->balance);
                $transaction->charge = 0;
                $transaction->trx_type = '+';
                $transaction->details = "Earnings from download '$image->title'";
                $transaction->image_id = $image->id;
                $transaction->trx = getTrx(12);
                $transaction->save();

            } 
        $download->save();
     }

     $badges = Badge::where('status',1)->get();
     foreach($badges as $badge){
         if($image->user->downloadCount() >= $badge->download_milestone){
            $userBadge =  UserBadge::firstOrNew(['user_id'=>$image->user->id]);
            $userBadge->badge_id = $badge->id;
            $userBadge->icon = $badge->icon;
            $userBadge->save();
          }
     }
 }

    public function getDownload(Request $request){

        $image = Image::whereTrackId($request->track)->first();
        if($image->premium == 1){
            if(Auth::guest()){
                $notify[]=['error','You can not download premium photo without purchase subscription'];
                return back()->withNotify($notify);
            }
            $user = auth()->user();
            if(!$user->subscription){
                $notify[]=['error','Please purchase a subscription first'];
                return back()->withNotify($notify);
            }
            $purchaseAt = showDateTime($user->subscription->purchase_at,'Y-m-d');
            $expireAt = showDateTime($user->subscription->expire_at,'Y-m-d');
           
            if($user->subscription->expire_at <= Carbon::now()){
              
                notify($user,'SUBSCRIPTION_EXP',[
                    'subscription_name' => $user->subscription->mainData->name,
                    'purchased_at' => showDateTime($user->subscription->purchase_at,'d M Y'),
                    'expired_at' => showDateTime($user->subscription->expire_at,'d M Y')
                ]);
                UserSubscription::where('user_id',$user->id)->delete();
                $notify[]=['error','Sorry your subscription plan has been expired'];
                return back()->withNotify($notify);
            }
        
            $dwnld =  Download::whereUserId($user->id)->wherePremium(1);
            $yearlyCount = $dwnld->whereBetween('date',[$purchaseAt, $expireAt])->count();
            $monthlyCount = $dwnld->whereMonth('date',Carbon::now()->month)->count();
            $dailyCount = $dwnld->whereDay('date',Carbon::now()->today())->count();


            if($yearlyCount >= $user->subscription->mainData->yearly_limit){
                $notify[]=['error','Sorry your yearly download limit is reached'];
                return back()->withNotify($notify);
            } elseif($monthlyCount >= $user->subscription->mainData->monthly_limit){
                $notify[]=['error','Sorry your monthly download limit is reached'];
                return back()->withNotify($notify);
            } elseif($dailyCount>=$user->subscription->mainData->daily_limit){
                $notify[]=['error','Sorry your daily download limit is reached'];
                return back()->withNotify($notify);
            }

        }

            $general = GeneralSetting::first();
            if($image->file){
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

            $notify[]=['info','Sorry problem occurred downloading original file, will be fixed soon'];
            return back()->withNotify($notify);
       
        
    }

    //like unlike
    public function like(Request $request)
    {

        $like = Like::whereUserId(auth()->id())->whereImageId($request->imageId)->first();
        if($like){
            $like->delete();
            return response()->json(['success' => 'You have unliked this photo']);
        }

        $like = new Like();
        $like->user_id = auth()->id();
        $like->image_id = $request->imageId;
        $like->like_status = 1;
        $like->save();
        return response()->json(['success' => 'You have liked this photo']);
        
    }


    public function collect()
    {
        $user = auth()->user();
        $collection = Collection::whereUserId($user->id)->get();
        return json_encode($collection);
    }


    public function collectStore(Request $request)
    {
        $collect = CollectionImage::where('collection_id',$request->collectionId)->where('image_id',$request->imageId)->first();
        if($collect){
            $collect->delete();
            return response()->json(['success' => 'Image removed from collection']);
        }
        CollectionImage::create([
            'collection_id'=>$request->collectionId,
            'image_id'=>$request->imageId
        ]);
 
       return response()->json(['success' => 'Image added to the collection']);
    
  }

  public function collectRemove($cId,$imgId)
  {
    $remove = CollectionImage::whereImageId($imgId)->whereCollectionId($cId)->first();
    $remove->delete();
    return response()->json(['success' => 'Image removed from the collection']);
  }

  public function categoryImages($id)
  {
      $category = Category::findOrFail($id);
      $images = Image::whereCategoryId($id)->whereStatus(1)->with(['user','category','likes','downloads'])->get();
      
      return view(activeTemplate().'frontend.categoryPhotos',compact('category','images'));
  }


//following
  public function follow(Request $request)
  {

        $follow = Follow::whereUserId($request->follower)->whereFollowed($request->followed)->first();
        
        if($follow){
            $follow->delete();
            return response()->json(['success' => 'You have unfollowed this user']);
        }
      
        $new = new Follow();
        $new->user_id = $request->follower;
        $new->followed = $request->followed;
        $new->save();
        return response()->json(['success' => 'You are now following this user']);
        
        
  }

  public function contributorProfile($id)
  {
      $profile = User::findOrFail($id);
      $images = $profile->images->where('status',1)->paginate(15);
      $user = auth()->user();
      $page_title='Contributor Profile';
      return view(activeTemplate().'frontend.profile',compact('profile','page_title','user','images'));
  }

  public function report(Request $request)
  {

        $rules = [];
        if($request->otherReason == null && $request->report == null ){
            $rules['report'] = 'required';
            $rules['otherReason'] = 'required';
        } 
       $request->validate($rules,[
          'report.required'=> 'Please tell us some valid reasons to report this photo',
          'otherReason.required'=> 'Please tell us some valid reasons to report this photo',
        ]);

     $report = Report::whereUserId($request->userId)->whereImageId($request->imageId)->first();
     if($report){
          $notify[]=['info','You have already reported this image'];
          return back()->withNotify($notify);
     }
     Report::create([
         'user_id'=>$request->userId,
         'image_id'=>$request->imageId,
         'reason'=> $request->report,
         'description' => $request->otherReason,
         'status' => 0
         
     ]);

      $notify[]=['success
      ','Your report has been taken'];
      return back()->withNotify($notify);
  }



  

}
