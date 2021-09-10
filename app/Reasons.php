<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reasons extends Model
{
    protected $guarded = [] ;

    public function reviewer()
    {
        return $this->belongsTo(Reviewer::class);
    }
}
