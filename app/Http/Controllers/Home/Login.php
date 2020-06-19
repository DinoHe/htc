<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Base;
use App\Http\Models\Members;
use App\Http\Models\Orders;
use App\Http\Models\SystemSettings;
use App\Jobs\RewardMiner;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Mews\Captcha\Facades\Captcha;

class Login extends Base
{

    public function login()
    {
        if ($this->request->method()=='POST'){
            $data = $this->request->all();
            $this->request->flashOnly(['phone','password']);

            $this->remakeSessionId();

            if(!Captcha::check($data['captcha'])){
                return redirect('home/login')->with('error','验证码错误');
            }
            if (Auth::attempt(['phone'=>$data['phone'], 'password'=>$data['password']])){
                $user = Auth::user();
                if ($user['activated'] != 0){
                    return redirect('home/login')->with('error','账户已被冻结，请联系客服');
                }
                //登录成功
                Auth::logoutOtherDevices($data['password']);
                //初始化币价
                $this->initCoin();

                return redirect('home/index');
            }
            return redirect('home/login')->with('error','用户名或密码错误');
        }
        return view('home.login');
    }

    /**
     * 退出登录
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout()
    {
        $tradeSales = Cache::get('tradeSales');
        $tradeBuy = Cache::get('tradeBuy');
        $online = Cache::get('online');
        //清除该用户在线记录
        if (!empty($online)){
            foreach ($online as $k => $on) {
                if ($k == Auth::id()){
                    array_splice($online,$k,1);
                    Cache::put('online',$online,Carbon::tomorrow()->setHours(1));
                    break;
                }
            }
        }
        //没有交易单，清除该用户资产缓存
        $flag = true;
        if (!empty($tradeSales)){
            foreach ($tradeSales as $tradeSale){
                if ($tradeSale['sales_member_id'] == Auth::id() && $tradeSale['order_status'] == Orders::ORDER_MATCHED){
                    $flag = false;
                    break;
                }
            }
        }
        if (!empty($tradeBuy)){
            foreach ($tradeBuy as $b){
                if ($b['buy_member_id'] == Auth::id() && $b['order_status'] == Orders::ORDER_MATCHED){
                    $flag = false;
                    break;
                }
            }
        }
        if ($flag) Cache::forget('assets'.Auth::id());
        Auth::logout();
        return redirect('home/login');
    }

}
