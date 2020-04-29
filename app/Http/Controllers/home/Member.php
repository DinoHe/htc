<?php


namespace App\Http\Controllers\home;


use Illuminate\Support\Facades\Auth;

class Member
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
        $user = Auth::user();
        $url = url('/').'/home/register?id='.$user->phone;
        return view('home.member.invite_link')->with('link', $url);
    }
}
