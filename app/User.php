<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'address' => 'object',
        'ver_code_send_at' => 'datetime'
    ];





    public function login_logs()
    {
        return $this->hasMany(UserLogin::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class)->orderBy('id','desc');
    }
    public function earnings()
    {
        return $this->hasMany(EarningLog::class,'contributor_id')->orderBy('id','desc');
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class)->where('status','!=',0);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class)->where('status','!=',0);
    }

   

    // SCOPES

    public function getFullnameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function scopeActive()
    {
        return $this->where('status', 1);
    }

    public function scopeBanned()
    {
        return $this->where('status', 0);
    }

    public function scopeEmailUnverified()
    {
        return $this->where('ev', 0);
    }

    public function scopeSmsUnverified()
    {
        return $this->where('sv', 0);
    }
    public function scopeEmailVerified()
    {
        return $this->where('ev', 1);
    }

    public function scopeSmsVerified()
    {
        return $this->where('sv', 1);
    }

   public function collections()
   {
       return $this->hasMany(Collection::class);
   }
   public function likes()
   {
       return $this->hasMany(Like::class);
   }
   public function follows()
   {
       return $this->hasMany(Follow::class);
   }

   public static function follow($userId,$followedId)
   {
       return Follow::whereUserId($userId)->whereFollowed($followedId)->first();
   }

   public function downloads()
   {
    return $this->hasMany(Download::class);
   }

   public function followRemoved($id,$followed)
   {
       return Follow::whereUserId($id)->whereFollowed($followed)->whereRemoved(1)->first();
   }
   public function comments()
   {
       return $this->hasMany(Comment::class);
   }

   public function images()
   {
       return $this->hasMany(Image::class);
   }
  
   public function latestImages()
   {
       return $this->hasMany(Image::class)->latest()->take(6);
   }

   public function myBadge()
   {
       return $this->hasOne(UserBadge::class,'user_id');
   }

   public function downloadCount()
    {
        $count = collect([]);
        $this->images->map(function($item) use ($count){
            $count->push($item->downloads->count()); 
        });
        return $count->sum();
    }
    public function likeCount()
    {
        $count = collect([]);
        $this->images->map(function($item) use ($count){
            $count->push($item->likes->count()); 
        });
        return $count->sum();
    }

    public function follower($id)
    {
        return Follow::whereFollowed($id)->whereRemoved(0)->count();
    }

    public function rejects()
    {
        return $this->hasMany(RejectedPhoto::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public  function plan($id = null)
    {

        if($id!=null){

            return UserSubscription::whereUserId(auth()->id())->whereSubscriptionId($id)->where('expire_at','>',Carbon::now())->first();
           
            
        }
        return UserSubscription::whereUserId(auth()->id())->where('expire_at','>',Carbon::now())->first();
    }

    public function subscription()
    {
        return $this->hasOne(UserSubscription::class);
    }

    public function rejectedPhoto()
    {
        return Image::whereUserId(auth()->id())->whereStatus(3)->paginate(15);
    }


  
    
}
