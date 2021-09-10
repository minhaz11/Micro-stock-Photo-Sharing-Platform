<?php

namespace App;


use Illuminate\Foundation\Auth\User as Authenticatable;

class Reviewer extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $casts = [
        'address' => 'object',
        'ver_code_send_at' => 'datetime'
    ];
    
    public function rejects()
    {
        return $this->hasMany(RejectedPhoto::class);
    }
    public function approved()
    {
        return $this->hasMany(ApprovedPhoto::class);
    }

    
    public function getFullnameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    
    public function login_logs()
    {
        return $this->hasMany(UserLogin::class,'reviewer_id');
    }

    public function reasons()
    {
        return $this->hasMany(Reasons::class);
    }
    
}
