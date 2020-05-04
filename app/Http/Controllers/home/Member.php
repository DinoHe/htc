<?php


namespace App\Http\Controllers\home;


use App\Http\Controllers\Base;
use App\Http\Models\IdentityAuths;
use App\Http\Models\SystemNotices;
use Illuminate\Support\Facades\Auth;

class Member extends Base
{
    public function member()
    {
        return view('home.member.member');
    }

    public function identityAuth()
    {
        if ($this->request->isMethod('post')){
            $data = $this->request->input();
            $hasIdcard = IdentityAuths::where('idcard',$data['idcard'])->first();
            if (!empty($hasIdcard)){
                return back()->withErrors(['idcard'=>'身份证号码已经被认证过'])->withInput();
            }
            $front = $this->request->file('id_front');
            $back = $this->request->file('id_back');
            if ($front->getSize()/(1024*1024) > 1){
                return back()->withErrors(['front'=>'身份证正面照片大于1M'])->withInput();
            }
            if ($back->getSize()/(1024*1024) > 1){
                return back()->withErrors(['back'=>'身份证背面照片大于1M'])->withInput();
            }
            $frontPath = $front->storeAs('public/idcardImg',$data['idcard'].'front.jpg');
            $backPath = $front->storeAs('public/idcardImg',$data['idcard'].'back.jpg');

            IdentityAuths::updateOrCreate([
                'member_id'=>Auth::id()
            ],[
                'name'=>$data['name'],
                'idcard'=>$data['idcard'],
                'alipay'=>$data['alipay'],
                'weixin'=>$data['weixin'],
                'bank_name'=>$data['bank_name']?:'',
                'credit_card'=>$data['credit']?:'',
                'idcard_front_img'=>$frontPath,
                'idcard_back_img'=>$backPath,
                'auth_status'=>IdentityAuths::AUTH_CHECKING
            ]);
            return redirect('home/member');

        }
        return view('home.member.identify_auth');
    }

    /**
     * 身份证号码验证码
     * @param $idCard
     * @return int
     */
    public function idCardCheck($idCard)
    {
        $url = 'https://qq.ip138.com/idsearch/index.asp?userid='.$idCard.'&action=idcard';
        $headerArray = array("Content-type:application/json;","Accept:application/json");
        $curl = curl_init();
        curl_setopt($curl,CURLOPT_URL,$url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($curl,CURLOPT_HTTPHEADER,$headerArray);
        $output = curl_exec($curl);
        curl_close($curl);
        if (!$output) return 0;
        preg_match('#<tbody>(.|\r\n)*</table>#',$output,$matches);
        $res1 = preg_match('#验证身份证号有误#',$matches[0],$matches1);
        if (!$res1){
            $res2 = preg_match('#<font color="red">提示(.|\r\n)*</font>#',$matches[0],$matches2);
            if (!$res2){
                return 1;
            }
        }
        return 0;
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
