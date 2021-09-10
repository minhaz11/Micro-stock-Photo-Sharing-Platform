<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mainData()
    {
        return $this->belongsTo(Subscription::class,'subscription_id');
    }
}
