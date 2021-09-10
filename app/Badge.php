<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $guarded = [];

    public function userBadges()
    {
        return $this->hasMany(UserBadge::class);
    }
}
