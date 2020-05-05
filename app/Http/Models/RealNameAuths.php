<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class RealNameAuths extends Model
{
//    认证状态
    const AUTH_FAIL = 0;
    const AUTH_SUCCESS = 1;
    const AUTH_CHECK_FAIL = 2;
    const AUTH_CHECKING = 3;

    protected $fillable = [
        'name','member_id','idcard','weixin','alipay','bank_name','bank_card','idcard_front_img','idcard_back_img',
        'auth_status','describes'
    ];

    public function getAuthStatusDesc($status)
    {
        switch ($status){
            case 0:
                return '未认证';
            case 1:
                return '认证成功';
            case 2:
                return '审核未通过';
            case 3:
                return '审核中';
        }
    }
}
