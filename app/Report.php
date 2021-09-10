<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $guarded = [];

    protected $casts =[
        'reason' => 'object'
    ];

    public function image()
    {
        return $this->belongsTo(Image::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function reviewer()
    {
        return $this->belongsTo(Reviewer::class);
    }
}
