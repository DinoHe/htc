<?php

namespace App\Http\Models;


use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;

class Admins extends User
{
    use Notifiable;

    const ACCOUNT_ON = 0;
    const ACCOUNT_BLOCKED = 1;

    protected $fillable = [
        'account','name','phone','weixin','password','blocked','role_id'
    ];

    public function roles()
    {
        return $this->belongsTo('App\Http\Models\Roles','role_id');
    }
}
