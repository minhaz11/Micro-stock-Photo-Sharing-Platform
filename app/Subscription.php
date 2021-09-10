<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $guarded = [];

    protected $casts = [
        'facilities'=>'object'
    ];

    public function userSubscription()
    {
        return $this->hasMany(UserSubscription::class,'subscription_id');
    }
}
