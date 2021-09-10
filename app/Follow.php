<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Follow extends Model
{
    use SoftDeletes;
    public function contributor()
    {
        return $this->belongsTo(Contributor::class);
    }
}
