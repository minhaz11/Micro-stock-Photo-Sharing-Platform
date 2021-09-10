<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $guarded = [];

   public function images()
   {
       return $this->belongsToMany(Image::class,'collections_images','collection_id','image_id');
   }

   public function contributor()
   {
       return $this->belongsTo(Contributor::class);
   }
   public function user()
   {
       return $this->belongsTo(User::class);
   }

   public function scopeLatest()
   {
       return $this->images()->latest()->first();
   }
}
