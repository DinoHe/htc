<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Base;
use App\Http\Models\Members;
use App\Http\Models\PhoneTmps;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class Register extends Base
{

    public function register()
    {
        if ($this->request->method()=='POST'){
            $data = $this->request->all();
            //验证表单
            $this->validator($data)->validate();

            $phone = Members::where('phone',$data['phone'])->first();
            if (!empty($phone)){
                return back()->withErrors(['phone'=>'手机号已注册'])->withInput();
            }
            //短信验证码验证
            $code = PhoneTmps::where('phone',$data['phone'])->where('code',$data['sms_verify'])->first();
            if (empty($code)) return back()->withErrors(['sms_verify'=>'验证码错误'])->withInput();
            if (time() - date_timestamp_get($code->updated_at) > 5*60) return back()->withErrors(['sms_verify'=>'验证码失效，请重新获取'])->withInput();

            //邀请码验证
            $invite = Members::where('invite_code',$data['invite'])->first();
            if (empty($invite)) {
                return back()->withErrors(['error'=>'无效的邀请码'])->withInput();
            }

            //写入数据库
            $status = Members::create([
                'phone' => $data['phone'],
                'password' => Hash::make($data['password']),
                'safe_password' => Hash::make($data['safe_password']),
                'invite_code' => $this->getInvite($data['phone'])
            ]);
            if ($status){
                return redirect('home/login');
            }
            return back()->withErrors(['register'=>'注册失败'])->withInput();
        }
        $invite = $this->request->input('invite', '');
        return view('home.register')->with('invite', $invite);
    }

    public function registerVerify()
    {
        $phone = $this->request->input('phone');
        $p = Members::where('phone',$phone)->first();
        if (!empty($p)){
            return $this->dataReturn(['status'=>1103,'message'=>'手机号已注册']);
        }
        $res = $this->sendSMS($phone);
        return $res;
    }

    public function getInvite($phone){
        $mdstring = md5(time().$phone);
        $invite = substr($mdstring, 7, 3).substr($mdstring, 23, 3);
        return $invite;
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
            'sms_verify' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'max:12', 'confirmed'],
            'safe_password' => ['required', 'string', 'size:6', 'confirmed'],
            'invite' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => '手机号不能为空',
            'sms_verify.required' => '验证码不能为空',
            'password.required' => '登录密码不能为空',
            'password.min' => '请输入6到12位登录密码',
            'password.max' => '请输入6到12位登录密码',
            'password.confirmed' => '两次密码输入不一致',
            'safe_password.required' => '安全密码不能为空',
            'safe_password.size' => '请输入6位安全密码',
            'safe_password.confirmed' => '两次安全密码输入不一致',
            'invite.required' => '请输入正确的邀请码'
        ];
    }

}
