<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    protected $guarded = [];

    public function image()
    { 
        return $this->belongsTo(Image::class);
    }

    public function downloader()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function contributor()
    {
        return  $this->belongsTo(User::class,'contributor_id');
    }


    public function downloadReport()
    {
        $imageId = $this->image->id;
        return $this->selectRaw("COUNT(CASE WHEN image_id = $imageId THEN image_id END) as total")
       ->selectRaw("DATE_FORMAT(created_at,'%M') as months")
       ->orderBy('created_at')
       ->groupBy(DB::Raw("MONTH(created_at)"))->get();
    }
}
