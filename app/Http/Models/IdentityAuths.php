<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class IdentityAuths extends Model
{
//    认证状态
    const AUTH_FAIL = 0;
    const AUTH_SUCCESS = 1;
    const AUTH_CHECK_FAIL = 2;
    const AUTH_CHECKING = 3;

    protected $fillable = [
        'name','member_id','idcard','weixin','alipay','bank_name','credit_card','idcard_front','idcard_back',
        'auth_status','describes'
    ];
}
