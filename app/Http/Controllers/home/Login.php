<?php

namespace App\Http\Controllers\Home;

use App\Http\Models\Jobs;
use App\Http\Models\Members;
use App\Jobs\SendMail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Mews\Captcha\Facades\Captcha;

class Login
{

    public function login(Request $request)
    {
        if ($request->method()=='POST'){
            $data = $request->all();
            $request->flashOnly(['phone','password']);
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
                return redirect('home/index');
            }
            return redirect('home/login')->with('error','用户名或密码错误');
        }
        return view('home.login');
    }

    //退出登录
    public function logout()
    {
        $tradeSales = Cache::get('tradeSales');
        $flag = true;
        if (!empty($tradeSales)){
            foreach ($tradeSales as $tradeSale){
                if (array_search(Auth::id(),$tradeSale)){
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
