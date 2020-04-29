<?php


namespace App\Http\Controllers\home;


use App\Http\Models\Members;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ResetPassword
{

    public function resetPassword(Request $request)
    {
        if ($request->method() == 'POST'){
            $data = $request->all();
            $members = Auth::user();
            dd(Auth::attempt(['phone'=>$members['phone'], 'safe_password'=>$data['old_safe_password']]));
            if (!Auth::attempt(['phone'=>$members['phone'], 'password'=>$data['old_password']])){
                return redirect('/home/reset')->with('error','旧登录密码错误');
            }
            if (!Auth::attempt(['phone'=>$members['phone'], 'safe_password'=>$data['old_safe_password']])){
                return redirect('/home/reset')->with('error','旧安全密码错误');
            }
            $status = Members::where('phone',$members['phone'])->update([
                'password' => Hash::make($data['new_password']),
                'safe_password' => Hash::make($data['new_safe_password'])
            ]);
            if ($status > 0){
                Auth::logout();
                return redirect('/home/login');
            }
        }
        return view('home.member.reset_password');
    }
}
