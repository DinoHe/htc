<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Base;
use App\Http\Models\Assets;
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
                //分享奖励
                $this->shareRewardMiner($user);

                return redirect('home/index');
            }
            return redirect('home/login')->with('error','用户名或密码错误');
        }
        return view('home.login');
    }

    public function download()
    {
        return response()->download(public_path('storage/app/htc.apk'));
    }

    /**
     * 退出登录
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout()
    {
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
        //资产缓存数据没有变化，清除该用户资产缓存
        $assets = Assets::where('member_id',Auth::id())->first();
        $assetsCache = Cache::get('assets'.Auth::id());
        if ($assets->balance == $assetsCache->balance){
            Cache::forget('assets'.Auth::id());
        }

        Auth::logout();
        return redirect('home/login');
    }

    private function shareRewardMiner($user)
    {
        $shareReward = SystemSettings::getSysSettingValue('share_reward');
        if ($shareReward == 'on'){
            RewardMiner::dispatch($user)->onQueue('give');
        }
    }
}
