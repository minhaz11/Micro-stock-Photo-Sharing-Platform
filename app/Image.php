<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    protected $guarded = [];
    protected $casts = [
        'tags'=>'array',
        'reviewing_status'=>'object',
    ];

    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function downloads()
    {
        return $this->hasMany(Download::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    public function views()
    {
        return $this->hasMany(Views::class);
    }

    public function collections()
    {
        return $this->belongsToMany(Collection::class,'collections_images');
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
            return $this->hasMany(Comment::class);
    }

    public function reject()
    {
        return $this->hasOne(RejectedPhoto::class);
    }
    public function approve()
    {
        return $this->hasOne(ApprovedPhoto::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
