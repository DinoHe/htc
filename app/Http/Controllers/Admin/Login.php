<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base;
use App\Http\Models\Admins;
use App\Http\Models\SystemLogs;
use Illuminate\Support\Facades\Auth;
use Mews\Captcha\Facades\Captcha;

class Login extends Base
{
    public function login()
    {
        if ($this->request->isMethod('post')){
            $data = $this->request->input();
            parent::remakeSessionId();

            if (!Captcha::check($data['captcha'])){
                return back()->withErrors(['loginError'=>'验证码错误'])->withInput();
            }
            if (Auth::guard('admin')->attempt(['account'=>$data['account'],'password'=>$data['password']])){
                if (Auth::guard('admin')->user()->blocked != Admins::ACCOUNT_ON){
                    return back()->withErrors(['loginError'=>'账号已被停用'])->withInput();
                }
                parent::initCoin();
                $ip = $this->request->ip();
                SystemLogs::createLog('登录',$data['account'],$ip,'登录：'.$data['account']);
                return redirect('admin/index');
            }else{
                return back()->withErrors(['loginError'=>'账号或密码错误'])->withInput();
            }
        }
        return view('admin.login');
    }

    public function logout()
    {
        $account = Auth::guard('admin')->user()->account;
        SystemLogs::createLog('登录',$account,$this->request->ip(),'退出登录：'.$account);
        Auth::guard('admin')->logout();
        return redirect('admin/index');
    }
}
