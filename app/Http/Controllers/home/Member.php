<?php


namespace App\Http\Controllers\home;


use App\Http\Controllers\Base;
use Illuminate\Support\Facades\Auth;

class Member extends Base
{
    public function member()
    {
        return view('home.member.member');
    }

    public function changePwd()
    {
        return view('home.member.changePwd');
    }

    public function link()
    {
        return view('home.member.invite_link');
    }

    public function qrcode()
    {
        $user = Auth::user();
        $url = url('/').'/home/register?invite='.$user->phone;
        $this->getQRcode($url);
    }

    public function notice()
    {
        return view('home.member.notice');
    }
}
