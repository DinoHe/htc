<?php


namespace App\Http\Controllers\home;


use App\Http\Controllers\Base;
use App\Http\Models\SystemNotices;
use Illuminate\Support\Facades\Auth;

class Member extends Base
{
    public function member()
    {
        return view('home.member.member');
    }

    public function identifyAuth()
    {
        return view('home.member.identify_auth');
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
        $notices = SystemNotices::all();
        return view('home.member.notice')->with('notices',$notices);
    }

    public function noticePreview($id)
    {
        $notice = SystemNotices::find($id);
        return view('home.member.notice_preview')->with('notice',$notice);
    }

    public function memberService()
    {
        return view('home.member.member_service');
    }

}
