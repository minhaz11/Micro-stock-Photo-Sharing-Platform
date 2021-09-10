<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBadge extends Model
{
    protected $guarded = [];

    public function badge()
    {
        return $this->belongsTo(Badge::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
