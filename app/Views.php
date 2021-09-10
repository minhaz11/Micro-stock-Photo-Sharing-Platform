<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Views extends Model
{
    protected $guarded = [];

    public function image()
    {
        return $this->belongsTo(Image::class);
    }
}
