<?php

namespace App\Http\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Members extends Authenticatable
{
    use Notifiable;

    //激活状态
    const ACTIVATED = 0;
    const ACTIVATE_NO = 1;
    const BLOCKED_TMP = 2;
    const BLOCKED_FOREVER = 3;

    protected $fillable = [
        'phone', 'password', 'safe_password', 'level_id', 'parentid','activated','deleted',
        'describes','invite_code'
    ];

    public function level()
    {
        return $this->hasOne('App\Http\Models\MemberLevels','level_id');
    }
}
