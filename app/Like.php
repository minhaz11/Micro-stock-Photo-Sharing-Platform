<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
   protected $guarded =[];

   public function user()
   {
       return $this->belongsTo(User::class);
   }
   public function contributor()
   {
       return $this->belongsTo(Contributor::class);
   }
   public function image( )
   {
    return $this->belongsTo(Image::class);
   }
}
