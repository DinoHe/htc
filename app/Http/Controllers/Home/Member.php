<?php


namespace App\Http\Controllers\Home;


use App\Http\Controllers\Base;
use App\Http\Models\Bills;
use App\Http\Models\Ideals;
use App\Http\Models\Members;
use App\Http\Models\MyMiners;
use App\Http\Models\Orders;
use App\Http\Models\RealNameAuths;
use App\Http\Models\Roles;
use App\Http\Models\SystemNotices;
use App\Http\Models\SystemSettings;
use App\Jobs\RewardCoin;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
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
        $member->authStatus = empty($auth)?'未认证':$auth->getAuthStatusDesc($auth->auth_status);
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
        }else{
            $auths = new RealNameAuths();
            $auths->auth_status_desc = '';
            $auths->name = '';
            $auths->idcard = '';
            $auths->weixin = '';
            $auths->bank_name = '';
            $auths->bank_card = '';
            $auths->auth_status = RealNameAuths::AUTH_FAIL;
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
        $regex = "#^"
            . "\\d{6}" // 6位地区码
            . "(18|19|([23]\\d))\\d{2}" // 年YYYY
            . "((0[1-9])|(10|11|12))" // 月MM
            . "(([0-2][1-9])|10|20|30|31)" // 日DD
            . "\\d{3}" // 3位顺序码
            . "[0-9Xx]" // 校验码
            . "$#";
        preg_match($regex,$idCard,$matches);
        if (empty($matches)) return 0;
        //身份证校验码校验
        $w = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8,4, 2]; //加权因子
        $s = 0;
        $idArray = str_split($idCard);
        for ($i=0;$i<count($w);$i++){
            $s += $w[$i] * $idArray[$i];
        }
        //如果校验码是X x， 代表10
        if ($idArray[17] == 'X' || $idArray[17] == 'x'){
            $s += 10;
        }else{
            $s += $idArray[17];
        }
        //mod 11余1,通过
        if ($s % 11 == 1){
            return 1;
        }
        return 0;
    }

    public function bill()
    {
        $bills = Bills::where('member_id',Auth::id())->orderBy('id','desc')->get();
        return view('home.member.bill')->with('bills',$bills);
    }

    /**
     * 待处理的订单
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function unprocessedOrder()
    {
        $unOrders = Orders::where('trade_status','<',Orders::TRADE_FINISHED)->cursor()
            ->filter(function ($orders){
                return $orders->buy_member_id == Auth::id() || $orders->sales_member_id == Auth::id();
            });

        foreach ($unOrders as $unOrder) {
            $timeArray = $unOrder->remainingTime($unOrder->updated_at);
            if (array_sum($timeArray) == 0){
                if ($unOrder->trade_status == Orders::TRADE_NO_PAY){
                    ////订单超时,交易取消，信用减2
                    $buy = Members::find($unOrder->buy_member_id);
                    $buy->credit -= 2;
                    $buy->save();
                    $unOrder->cancelTrade($unOrder);
                }elseif ($unOrder->trade_status == Orders::TRADE_NO_CONFIRM){
                    //超时，交易自动完成
                    $this->finishTrade($unOrder->id);
                }

            }
        }

        return view('home.trade.unprocessedOrder')->with('unOrders',$unOrders);
    }

    /**
     * 订单详情
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function orderPreview($id)
    {
        $previews = Orders::where('id',$id)->first();
        if ($previews->trade_status == Orders::TRADE_FINISHED){
            return back()->withErrors(['error'=>'交易已完成'])->withInput();
        }
        //买家信息
        $buyMember = $previews->buyMember;
        $previews->buyMemberCredit = $buyMember->credit;
        $previews->buyMemberPhone = $buyMember->phone;
        $previews->buyMemberW = $buyMember->realNameAuth->weixin;
        //卖家信息
        $salesMember = $previews->salesMember;
        $salesMemberRealNameAuth = $salesMember->realNameAuth;
        $previews->salesMemberName = $salesMemberRealNameAuth->name;
        $previews->salesMemberCredit = $salesMember->credit;
        $previews->salesMemberAlipay = $salesMember->phone;
        $previews->salesMemberBankName = $salesMemberRealNameAuth->bank_name;
        $previews->salesMemberBankCard = $salesMemberRealNameAuth->bank_card;
        $previews->salesMemberW = $salesMemberRealNameAuth->weixin;
        $previews->remaining = $previews->remainingTime($previews->updated_at);

        return view('home.trade.orderPreview')->with('previews',$previews);
    }

    /**
     * 投诉
     * @param $orderId
     * @return false|string
     */
    public function tradeComplaint($orderId)
    {
        Orders::where('id',$orderId)->update(['describes'=>'投诉假图']);
        return $this->dataReturn(['status'=>0]);
    }

    /**
     * 完成付款，上传截图
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function finishPay()
    {
        $file = $this->request->file('pay_img');
        if (empty($file)) return back()->withErrors(['tradeError'=>'请选择要上传的截图'])->withInput();
        if ($file->getSize()/(1024*1024) > 1) return back()->withErrors(['uploadError'=>'请上传小于1M的截图'])->withInput();
        $path = $file->store('public/payImg');
        if (empty($path)){
            return back()->withErrors(['tradeError'=>'上传失败，请稍后重新上传'])->withInput();
        }else{
            $res = Orders::where('id',$this->request->input('id'))->update([
                'payment_img' => substr($path,6),
                'trade_status' => Orders::TRADE_NO_CONFIRM
            ]);
            if ($res){
                return redirect('home/unprocessedOrder');
            }
        }
        return back()->withErrors(['tradeError'=>'系统错误'])->withInput();
    }

    /**
     * 交易确认
     */
    public function finishPayConfirm()
    {
        $orderId = $this->request->input('id');
        $res = $this->finishTrade($orderId);
        if (!$res){
            return back()->withErrors(['tradeError'=>'系统错误'])->withInput();
        }
        return redirect('home/unprocessedOrder');
    }

    private function finishTrade($orderId)
    {
        $order = Orders::where('id',$orderId)->first();
        $buyAssets = Cache::get('assets'.$order->buy_member_id);
        $salesAssets = Cache::get('assets'.$order->sales_member_id);
        DB::beginTransaction();
        //买家资产确认
        $buyAssets->balance += $order->trade_number;
        $buyAssets->buys += $order->trade_number;
        //卖家资产确认
        $handRate = SystemSettings::getSysSettingValue('trade_handling_charge');
        $n = $order->trade_number*(1+$handRate);
        $salesAssets->blocked_assets -= $n;
        //订单完成交易
        $order->trade_status = Orders::TRADE_FINISHED;

        $orderRes = $order->save();
        $buyRes = $buyAssets->save();
        $salesRes = $salesAssets->save();

        if (!$orderRes || !$buyRes || !$salesRes) {
            DB::rollBack();
            return false;
        }
        DB::commit();
        //完成交易买家信用+1
        $buyMember = Members::find($order->buy_member_id);
        if ($buyMember->credit < 100){
            $buyMember->credit += 1;
            $buyMember->save();
        }

        //奖励上级币
        $isReward = SystemSettings::getSysSettingValue('subordinate_buy_reward');
        if ($isReward == 'on' && $leaderId = parent::levelCheck($order->buy_member_id)){
            RewardCoin::dispatch($order->trade_number,$leaderId)->onQueue('give');
        }

        Cache::put('assets'.$order->buy_member_id,$buyAssets,Carbon::tomorrow());
        Cache::put('assets'.$order->sales_member_id,$salesAssets,Carbon::tomorrow());
        Bills::createBill($order->buy_member_id,'余额-买入','+'.$order->trade_number);
        Bills::createBill($order->sales_member_id,'余额-卖出','-'.$n);

        return true;
    }

    /**
     * 交易记录
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function record()
    {
        $orders = Orders::where('trade_status','>=',Orders::TRADE_FINISHED)->orderBy('updated_at','desc')->cursor()
            ->filter(function ($orders){
                return $orders->buy_member_id == Auth::id() || $orders->sales_member_id == Auth::id();
            });

        return view('home.trade.record')->with('orders',$orders);
    }

    /**
     * 我的团队
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function team()
    {
        $member = Auth::user();
        $myMiners = new MyMiners();
        $subordinatesArray = $member->getSubordinates($member->id);
        $subordinates = $subordinatesArray[0];
        $realNameAuthedNumber = $subordinatesArray[1];
        $hashrates = $myMiners->hashrateSum($member->id);
        $teamHashrates = $hashrates + $subordinatesArray[2];

        return view('home.member.team',['subordinates'=>$subordinates,'realNameAuthedNumber'=>$realNameAuthedNumber,
            'teamHashrates'=>$teamHashrates]);
    }

    public function link()
    {
        $link = url('/').'/home/register?invite='.Auth::user()->invite;
        return view('home.member.invite_link',['link'=>$link]);
    }

    public function qrcode($url)
    {
        $this->getQRcode(decrypt($url));
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
        $url = 'http://api.coindog.com/api/v1/ticks/BITFINEX?unit=cny';
        $curl = curl_init();
        curl_setopt($curl,CURLOPT_URL,$url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
        $output = curl_exec($curl);
        curl_close($curl);
        $output = json_decode($output);

        return view('home.member.quotations')->with('quotations',$output);
    }

    public function noticePreview($id)
    {
        $notice = SystemNotices::find($id);
        return view('home.member.notice_preview')->with('notice',$notice);
    }

    public function memberService()
    {
        $service = Roles::where('name','客服')->first();
        return view('home.member.member_service',['service'=>$service]);
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
            $account = Auth::user()->phone;
            Ideals::create(['account'=>$account,'content'=>$this->request->input('content')]);
            return $this->dataReturn(['status'=>0,'message'=>'提交成功']);
        }
        return view('home.member.ideal');
    }

}
