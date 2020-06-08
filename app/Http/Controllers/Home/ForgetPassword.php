<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Base;
use App\Http\Models\Members;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ForgetPassword extends Base
{

    public function forgetPassword()
    {
        if ($this->request->method()=='POST'){
            $data = $this->request->all();
            $member = Members::where('phone',$data['phone'])->first();
            if (empty($member)){
                return back()->withErrors(['phone'=>'该账号不存在，请先注册'])->withInput();
            }
            //闪存用户数据，前台显示
            $this->request->session()->flash('data',$data);
            //验证表单
            $this->validator($data)->validate();
            //更新密码
            $status = Members::where('phone',$data['phone'])->update([
                'password' => Hash::make($data['password']),
                'safe_password' => Hash::make($data['safe_password']),
            ]);
            if ($status > 0){
                return redirect('home/login');
            }else{
                return back()->withErrors(['phone'=>'重置密码失败'])->withInput();
            }
        }
        return view('home.forget');
    }

    public function forgetVerify()
    {
        $phone = $this->request->input('phone');
        $p = Members::where('phone',$phone)->first();
        if (empty($p)){
            return $this->dataReturn(['status'=>1104,'message'=>'该账号不存在，请先注册']);
        }
        $res = $this->sendSMS($phone);
        return $res;
    }

    //验证
    public function validator(array $data)
    {
        return Validator::make($data, $this->rules(), $this->messages());
    }

    public function rules()
    {
        return [
            'phone' => ['required', 'string'],
            'captcha' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'max:12', 'confirmed'],
            'safe_password' => ['required', 'string', 'size:6', 'confirmed'],
        ];
    }

    public function messages()
    {
        return [
            'phone' => '手机号不能为空',
            'captcha' => '验证码不能为空',
            'password' => '登录密码不能为空',
            'password.min' => '请输入6到12位登录密码',
            'password.confirmed' => '两次密码输入不一致',
            'safe_password' => '安全密码不能为空',
            'safe_password.size' => '请输入6位安全密码',
            'safe_password.confirmed' => '两次安全密码输入不一致',
        ];
    }

}
