<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RejectedPhoto extends Model
{
    protected $guarded = [];

    protected $casts = [
        'reasons'=>'object'
    ];

    public function image()
    {
        return $this->belongsTo(Image::class)->whereNull('deleted_at');
    }
    public function reviewer()
    {
        return $this->belongsTo(Reviewer::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
