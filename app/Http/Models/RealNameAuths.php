<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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

    public function member()
    {
        return $this->belongsTo('App\Http\Models\Members','member_id');
    }

    public function getAuthStatusDesc($status)
    {
        switch ($status){
            case RealNameAuths::AUTH_SUCCESS:
                return '已认证';
            case RealNameAuths::AUTH_CHECK_FAIL:
                return '未通过';
            case RealNameAuths::AUTH_CHECKING:
                return '待审核';
            default:
                return '未认证';
        }
    }

    /**
     * 实名认证验证
     * @return bool
     */
    public function realNameAuthCheck()
    {
        $realName = self::where('member_id',Auth::id())->first();
        if (empty($realName) || $realName->auth_status != RealNameAuths::AUTH_SUCCESS){
            return false;
        }
        return true;
    }
}
