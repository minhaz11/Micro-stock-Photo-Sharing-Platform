<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApprovedPhoto extends Model
{
    protected $guarded =[];

    public function reviewer()
    {
        return $this->belongsTo(Reviewer::class);
    }
    public function image()
    {
        return $this->belongsTo(Image::class);
    }
}
