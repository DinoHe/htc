<?php


namespace App\Http\Controllers\home;


use App\Http\Controllers\Base;
use App\Http\Models\Bills;
use App\Http\Models\Ideals;
use App\Http\Models\Members;
use App\Http\Models\MyMiners;
use App\Http\Models\RealNameAuths;
use App\Http\Models\SystemNotices;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class Member extends Base
{
    /**
     * 会员中心
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function member()
    {
        //等级初始化
        parent::initLevel();
        $member = Auth::user();
        $auth = $member->realNameAuth;
        $member->authStatus = $auth->getAuthStatusDesc($auth->auth_status);
        $member->level = $member->level->level_name;
        $assets = Cache::get('assets'.$member->id);
        $myMiners = MyMiners::where('member_id',$member->id)->where('run_status',MyMiners::RUNNING)->count();
        $member->minerNumber = $myMiners;
        $member->teamsNumber = count($member->getSubordinates($member->id)[0]);

        return view('home.member.member',['member'=>$member,'assets'=>$assets]);
    }

    /**
     * 实名认证
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function realNameAuth()
    {
        if ($this->request->isMethod('post')){
            $data = $this->request->input();
            $hasIdcard = RealNameAuths::where('idcard',$data['idcard'])->first();
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
            $backPath = $back->storeAs('public/idcardImg',$data['idcard'].'back.jpg');

            RealNameAuths::updateOrCreate([
                'member_id'=>Auth::id()
            ],[
                'name'=>$data['name'],
                'idcard'=>$data['idcard'],
                'alipay'=>$data['alipay'],
                'weixin'=>$data['weixin'],
                'bank_name'=>$data['bank_name']?:'',
                'bank_card'=>$data['bank_card']?:'',
                'idcard_front_img'=>substr($frontPath,6),
                'idcard_back_img'=>substr($backPath,6),
                'auth_status'=>RealNameAuths::AUTH_CHECKING
            ]);
            return redirect('home/member');
        }
        $auths = RealNameAuths::where('member_id',Auth::id())->first();
        if (!empty($auths)){
            $auths->auth_status_desc = $auths->getAuthStatusDesc($auths->auth_status);
        }
        return view('home.member.real-name_auth')->with('auths',$auths);
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

    public function bill()
    {
        $bills = Bills::where('member_id',Auth::id())->orderBy('id','desc')->get();
        return view('home.member.bill')->with('bills',$bills);
    }

    /**
     * 我的团队
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function team()
    {
        $members = new Members();
        $subordinatesArray = $members->getSubordinates(Auth::id());
        $subordinates = $subordinatesArray[0];
        $realNameAuthedNumber = $subordinatesArray[1];

        return view('home.member.team',['subordinates'=>$subordinates,'realNameAuthedNumber'=>$realNameAuthedNumber]);
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

    /**
     * 市场行情
     *
     */
    public function quotations()
    {
        $url = 'https://www.hqz.com/api/index/get_side_concept_coin/?conceptid=12';
        $curl = curl_init();
        curl_setopt($curl,CURLOPT_URL,$url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
        $output = curl_exec($curl);
        curl_close($curl);
        $output = json_decode($output);
        $quotations = $output->data;

        return view('home.member.quotations')->with('quotations',$quotations);
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

    /**
     * 修改密码
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function resetPassword()
    {
        if ($this->request->isMethod('post')){
            $data = $this->request->input();
            $members = Auth::user();
            if (!Hash::check($data['old_password'],$members->password)){
                return back()->withErrors(['passwordError'=>'原登录密码错误']);
            }
            if (!Hash::check($data['old_safe_password'],$members->safe_password)){
                return back()->withErrors(['safePasswordError'=>'原安全密码错误']);
            }
            $status = Members::where('id',$members->id)->update([
                'password' => Hash::make($data['new_password']),
                'safe_password' => Hash::make($data['new_safe_password'])
            ]);
            if ($status){
                Auth::logout();
                return redirect('home/login');
            }
        }
        return view('home.member.reset_password');
    }

    /**
     * 建议
     * @return false|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function ideal()
    {
        if ($this->request->isMethod('post')){
            Ideals::create(['content'=>$this->request->input('content')]);
            return $this->dataReturn(['status'=>0,'message'=>'提交成功']);
        }
        return view('home.member.ideal');
    }

}
