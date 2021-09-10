<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EarningLog extends Model
{
    public function image()
    {
        return $this->belongsTo(Image::class,'image_id')->withDefault();
    }
}
