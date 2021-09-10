<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function images()
    {
        return $this->hasMany(Image::class)->whereStatus(1)->latest();
    }
    public function rejects()
    {
        return $this->hasMany(RejectedPhoto::class);
    }
    
}
