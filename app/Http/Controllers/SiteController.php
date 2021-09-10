<?php

namespace App\Http\Controllers;

use App\Page;
use App\Image;
use App\Views;
use App\Comment;
use App\Category;
use App\Download;
use App\Frontend;
use App\Language;
use Carbon\Carbon;
use App\Subscriber;
use App\SupportTicket;
use App\GeneralSetting;
use App\SupportMessage;
use App\SupportAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class SiteController extends Controller
{
    public function __construct(){
        $this->activeTemplate = activeTemplate();
    }

    public function index(){
        $count = Page::where('tempname',$this->activeTemplate)->where('slug','home')->count();
        if($count == 0){
            $page = new Page($in);
            $page->tempname = $this->activeTemplate;
            $page->name = 'HOME';
            $page->slug = 'home';
            $page->save();
        }

        $data['page_title'] = 'Home';
        $data['sections'] = Page::where('tempname',$this->activeTemplate)->where('slug','home')->firstOrFail();
        return view($this->activeTemplate . 'home', $data);
    }
    public function pages($slug)
    {
        $page = Page::where('tempname',$this->activeTemplate)->where('slug',$slug)->firstOrFail();
        $data['page_title'] = $page->name;
        $data['sections'] = $page;
        return view($this->activeTemplate . 'pages', $data);
    }


    public function contact()
    {
        $data['page_title'] = "Contact Us";
        return view($this->activeTemplate . 'contact', $data);
    }


    public function contactSubmit(Request $request)
    {
        $ticket = new SupportTicket();
        $message = new SupportMessage();

        $imgs = $request->file('attachments');
        $allowedExts = array('jpg', 'png', 'jpeg', 'pdf');

        $this->validate($request, [
            'attachments' => [
                'sometimes',
                'max:4096',
                function ($attribute, $value, $fail) use ($imgs, $allowedExts) {
                    foreach ($imgs as $img) {
                        $ext = strtolower($img->getClientOriginalExtension());
                        if (($img->getSize() / 1000000) > 2) {
                            return $fail("Images MAX  2MB ALLOW!");
                        }
                        if (!in_array($ext, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg, pdf images are allowed");
                        }
                    }
                    if (count($imgs) > 5) {
                        return $fail("Maximum 5 images can be uploaded");
                    }
                },
            ],
            'name' => 'required|max:191',
            'email' => 'required|max:191',
            'subject' => 'required|max:100',
            'message' => 'required',
        ]);


        $random = getNumber();

        $ticket->user_id = auth()->id();
        $ticket->name = $request->name;
        $ticket->email = $request->email;


        $ticket->ticket = $random;
        $ticket->subject = $request->subject;
        $ticket->last_reply = Carbon::now();
        $ticket->status = 0;
        $ticket->save();

        $message->supportticket_id = $ticket->id;
        $message->message = $request->message;
        $message->save();

        $path = imagePath()['ticket']['path'];

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $image) {
                try {
                    $attachment = new SupportAttachment();
                    $attachment->support_message_id = $message->id;
                    $attachment->image = uploadImage($image, $path);
                    $attachment->save();

                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Could not upload your ' . $image];
                    return back()->withNotify($notify)->withInput();
                }

            }
        }
        $notify[] = ['success', 'ticket created successfully!'];

        return redirect()->route('ticket.view', [$ticket->ticket])->withNotify($notify);
    }

    public function changeLanguage($lang = null)
    {
        $language = Language::where('code', $lang)->first();
        if (!$language) $lang = 'en';
        session()->put('lang', $lang);
        return redirect()->back();
    }


    public function placeholderImage($size = null){
        if ($size != 'undefined') {
            $size = $size;
            $imgWidth = explode('x',$size)[0];
            $imgHeight = explode('x',$size)[1];
            $text = $imgWidth . '×' . $imgHeight;
        }else{
            $imgWidth = 150;
            $imgHeight = 150;
            $text = 'Undefined Size';
        }
        $fontFile = realpath('assets/font') . DIRECTORY_SEPARATOR . 'RobotoMono-Regular.ttf';
        $fontSize = round(($imgWidth - 50) / 8);
        if ($fontSize <= 9) {
            $fontSize = 9;
        }
        if($imgHeight < 100 && $fontSize > 30){
            $fontSize = 30;
        }

        $image     = imagecreatetruecolor($imgWidth, $imgHeight);
        $colorFill = imagecolorallocate($image, 100, 100, 100);
        $bgFill    = imagecolorallocate($image, 175, 175, 175);
        imagefill($image, 0, 0, $bgFill);
        $textBox = imagettfbbox($fontSize, 0, $fontFile, $text);
        $textWidth  = abs($textBox[4] - $textBox[0]);
        $textHeight = abs($textBox[5] - $textBox[1]);
        $textX      = ($imgWidth - $textWidth) / 2;
        $textY      = ($imgHeight + $textHeight) / 2;
        header('Content-Type: image/jpeg');
        imagettftext($image, $fontSize, 0, $textX, $textY, $colorFill, $fontFile, $text);
        imagejpeg($image);
        imagedestroy($image);
    }

    public function commentStore(Request $request)
    {

        $comment = new Comment();
        $comment->image_id = $request->image_id;
        $comment->comment = $request->comment;
        $comment->user_id = auth()->id();
        $comment->save();
        return response()->json(['success'=>'Your comment has been posted']);
    }

    public function viewAllPhotos(Request $request)
    {
       
        $empty_message = "No Photos";
        $categories = Category::all();
        
        if($request->filter)
        {
            $page_title = "All ".str_replace('-',' ',$request->filter) ." photos";
            if($request->filter == 'free'){
                if($request->timeframe){
                   $query = Image::whereStatus(1)->where('premium',0);
                    if($request->timeframe == 1) {
                        
                        $query = $query->whereDate('created_at',Carbon::now());
                    }else{
                        $query = $query->whereDate('created_at','>',Carbon::now()->subDays($request->timeframe))->whereDate('created_at','<=',Carbon::now());
                    }

                    $images = $query->with(['category','user','likes','downloads'])
                    ->paginate(30);

                } else{

                    $images = Image::whereStatus(1)->where('premium',0)->with(['category','user','likes','downloads'])->paginate(30);
                }
                $view = 'frontend.allPhotos';

            } else if($request->filter == 'premium'){
               
                if($request->timeframe){
                    $query = Image::whereStatus(1) ->where('premium',1);
                     if($request->timeframe == 1) {
                         
                         $query = $query->whereDate('created_at',Carbon::now());
                     }else{
                         $query = $query->whereDate('created_at','>',Carbon::now()->subDays($request->timeframe))->whereDate('created_at','<=',Carbon::now());
                     }
 
                     $images = $query->with(['category','user','likes','downloads'])
                     ->paginate(30);
 
                 } else{
 
                     $images = Image::whereStatus(1)->where('premium',1)->with(['category','user','likes','downloads'])->paginate(30);
                 }
                $view = 'frontend.allPhotos';

            } else if($request->filter == 'top-premium'){

                if($request->timeframe){
                     $query = Download::where('premium',1);
                     if($request->timeframe == 1) {
                         
                         $query = $query->whereDate('created_at',Carbon::now());
                     }else{
                         $query = $query->whereDate('created_at','>',Carbon::now()->subDays($request->timeframe))->whereDate('created_at','<=',Carbon::now());
                     }
 
                     $images = $query->whereHas('image',function($image){
                        $image->where('status',1);
                    })->orderBy('image_id','desc')->groupBy('image_id')->paginate(30);
 
                 } else{
 
                    $images = Download::where('premium',1)->whereHas('image',function($image){
                        $image->where('status',1);
                    })->orderBy('image_id','desc')->groupBy('image_id')->paginate(30);
                 }

               
                 $view = 'frontend.photoFilter';

            } else if($request->filter == 'top-free'){

                if($request->timeframe){
                    $query = Download::where('premium',0);
                    if($request->timeframe == 1) {
                        
                        $query = $query->whereDate('created_at',Carbon::now());
                    }else{
                        $query = $query->whereDate('created_at','>',Carbon::now()->subDays($request->timeframe))->whereDate('created_at','<=',Carbon::now());
                    }

                    $images = $query->whereHas('image',function($image){
                       $image->where('status',1);
                   })->orderBy('image_id','desc')->groupBy('image_id')->paginate(30);

                } else{

                   $images = Download::where('premium',0)->whereHas('image',function($image){
                       $image->where('status',1);
                   })->orderBy('image_id','desc')->groupBy('image_id')->paginate(30);
                }
                
                $view = 'frontend.photoFilter';   

            } else if($request->filter == 'most-downloads'){

                if($request->timeframe){
                    if($request->timeframe == 1) {
                        $query = Download::whereDate('created_at',Carbon::now());
                    }else{
                        $query = Download::whereDate('created_at','>',Carbon::now()->subDays($request->timeframe))->whereDate('created_at','<=',Carbon::now());
                    }

                    $images = $query->whereHas('image',function($image){
                       $image->where('status',1);
                   })->orderBy('image_id','desc')->groupBy('image_id')->paginate(30);

                } else{

                    $images = Download::whereHas('image',function($image){
                        $image->where('status',1);
                    })->orderBy('image_id','desc')->groupBy('image_id')->paginate(30);
                }

               
                $view = 'frontend.photoFilter';   

            } else if($request->filter == 'most-views'){

                if($request->timeframe){
                    if($request->timeframe == 1) {
                        $query = Views::whereDate('created_at',Carbon::now());
                    }else{
                        $query = Views::whereDate('created_at','>',Carbon::now()->subDays($request->timeframe))->whereDate('created_at','<=',Carbon::now());
                    }

                    $images = $query->whereHas('image',function($image){
                       $image->where('status',1);
                   })->orderBy('image_id','desc')->groupBy('image_id')->paginate(30);

                } else{
                    $images = Views::whereHas('image',function($image){
                        $image->where('status',1);
                    })->orderBy('image_id','desc')->groupBy('image_id')->paginate(30);
                }

                $view = 'frontend.photoFilter';   
            } else if($request->filter == 'latest'){
                if($request->timeframe){
                   $query = Image::latest()->whereStatus(1);
                    if($request->timeframe == 1) {
                        
                        $query = $query->whereDate('created_at',Carbon::now());
                    }else{
                        $query = $query->whereDate('created_at','>',Carbon::now()->subDays($request->timeframe))->whereDate('created_at','<=',Carbon::now());
                    }
                    $images = $query->with(['category','user','likes','downloads'])
                    ->paginate(30);

                } else{
                    $images = Image::latest()->whereStatus(1)->with(['category','user','likes','downloads'])->paginate(30);
                }
                $view = 'frontend.allPhotos';
            }
            else {
                $images = collect([])->paginate(30);
                $view = 'frontend.allPhotos';
            }
          
        } else{
            $page_title = "All photos";
            if($request->timeframe){
                $query = Image::whereStatus(1);
                 if($request->timeframe == 1) {
                     $query = $query->whereDate('created_at',Carbon::now());
                 }else{
                     $query = $query->whereDate('created_at','>',Carbon::now()->subDays($request->timeframe))->whereDate('created_at','<=',Carbon::now());
                 }
                 $images = $query->with(['category','user','likes','downloads'])
                 ->paginate(30);
             } else{
                 $images = Image::whereStatus(1)->with(['category','user','likes','downloads'])->paginate(30);
             }
            $view = 'frontend.allPhotos';
        }
        return view($this->activeTemplate.$view,compact('page_title','empty_message','images','categories'));
    }

    public function searchPhotos(Request $request)
    {
        $value = $request->search;
        $page_title = "Search Results";
        $empty_message = "No result found";
        $images = Image::whereStatus(1)->where('title','like',"%$value%")
          ->orWhere('tags','like',"%$value%")->latest()->with(['category','user','likes','downloads'])->paginate(20);
          $categories = Category::all();
          return view(activeTemplate().'frontend.allPhotos',compact('page_title','empty_message','images','value','categories'));
    }

    public function getDownload()
    {
        $general = GeneralSetting::first();
        $filepath ='assets/licence/licence.txt';
        $extension = getExtension($filepath);
        $fileName =  $general->sitename.'_'.'licence'.'.'.$extension;
        $headers = [

            'Cache-Control' => 'no-store, no-cache'
        ];


        return response()->download( $filepath, $fileName, $headers);
    }

    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email'=> 'required|email'
        ]);

        if($validator->fails()){
            return response()->json(['error'=>'Email is not valid']);
        }

        Subscriber::create([
            'email'=> $request->email
        ]);

        return response()->json(['success'=>'Subscribed Successfully']);
    }

    public function faq()
    {
        $page_title = 'FAQ';
        $faq = Frontend::where('data_keys','faq.content')->first();
        $faqs = Frontend::where('data_keys','faq.element')->get();
        return view($this->activeTemplate.'sections.faq',compact('page_title','faqs','faq'));

    }

    public function policyAndTerms($slug, $id)
    {
        $policy = Frontend::findOrFail($id);
        $page_title= $policy->data_values->title;
        return view($this->activeTemplate.'sections.policies',compact('policy','page_title'));
    }


}
