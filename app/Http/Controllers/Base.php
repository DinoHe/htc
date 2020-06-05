<?php
namespace App\Http\Controllers;

use App\Http\Controllers\home\Member;
use App\Http\Models\Coins;
use App\Http\Models\Members;
use App\Http\Models\MyMiners;
use App\Http\Models\SystemSettings;
use App\Libraries\SMS\SendTemplateSMS;
use App\Http\Models\PhoneTmps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Base extends Controller
{
    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
     * 初始化计算矿机收益
     * @param $miners
     */
    protected function initMiners($miners)
    {
        if ($miners->isEmpty()) return false;
        foreach ($miners as $miner){
            if ($miner->run_status != MyMiners::RUNNING){
                continue;
            }
            $timeDiff = (int)((time() - date_timestamp_get(date_create($miner->updated_at))) / 3600); //h
            if ($timeDiff > 15*24){ //超过15天未收取则失效
                $miner->run_status = MyMiners::RUN_EXPIRED;
                $miner->save();
                continue;
            }
            $collect = round($miner->nph * $timeDiff, 2);
            $maxCollect = $miner->total_dig - $miner->dug;
            if ($collect >= $maxCollect){
                $collect = $maxCollect;
            }
            $miner->no_collect = $collect;
        }
    }

    protected function initCoin()
    {
        $coins = Coins::orderBy('id','desc')->first();
        $coinPriceStep = SystemSettings::getSysSettingValue('coin_price_step');
        if (empty($coins)){
            Coins::create(['price'=>0.1]);
        }elseif (date_format($coins->created_at,'Y-m-d') != date('Y-m-d')){
            Coins::create(['price'=>$coins->price+$coinPriceStep]);
        }
    }

    protected function initLevel()
    {
        $user = Auth::user();
        $myMinerIdMax = MyMiners::where('run_status',MyMiners::RUNNING)->where('member_id',$user->id)->max('miner_id');
        if (empty($myMinerIdMax)){
            $user->level_id = 1;
        }elseif ($myMinerIdMax == $user->level_id){
            return false;
        }else{
            $user->level_id = $myMinerIdMax;
        }
        $user->save();
    }

    /**
     * 奖励上级受等级限制
     * @param $memberId
     * @return bool
     */
    protected function levelCheck($memberId){
        $levelConstraint = SystemSettings::getSysSettingValue('level_constraint');
        if ($levelConstraint == 'on'){
            $member = Members::find($memberId);
            $leader = Members::find($member->parentid);
            if (!empty($leader) && $leader->level_id > $member->level_id){
                return $leader->id;
            }
        }

        return false;
    }

    public function getQRcode($url)
    {
        \QRcode::png($url,false,QR_ECLEVEL_L,3,1);
    }

    /**
     * 发送短信验证码
     * @param $phone
     * @return false|mixed|string
     */
    public function sendSMS($phone)
    {
        $phoneTmp = PhoneTmps::where('phone',$phone)->first();
        if (!empty($phoneTmp)){
            $t = time() - date_timestamp_get($phoneTmp->updated_at);
            if($t <= 60){
                return $this->dataReturn(['status'=>1104,'message'=>'发送频繁，请'.(60 - $t).'s后获取']);
            }
        }
        $sendSMS = new SendTemplateSMS();
        $code = rand(1000,9999);
        $sendRes = $sendSMS->sendTemplateSMS($phone, array($code,5), 1);
        $res = json_decode($sendRes,true);
        $res['status'] = 0;
        if ($res['status'] == 0) {
            $tmp = PhoneTmps::updateOrCreate(
                ['phone' => $phone],
                ['code' => $code]
            );
            if (!empty($tmp)) {
                return $this->dataReturn(['status'=>0,'message'=>'发送成功']);
            }else{
                return $this->dataReturn(['status'=>1201,'message'=>'SQL异常']);
            }
        }
        return $res;
    }

    /**
     * 过滤二维数组中的元素, 无法过滤中文
     * @param $obj
     * @param $array
     * @return array
     */
    protected function array2Filter($obj,$array):array
    {
        return array_filter($array,function ($array) use ($obj){
            if (array_search($obj,$array)) return true;
            return false;
        });
    }

    protected function remakeSessionId()
    {
        $this->request->session()->regenerate();
    }

    protected function dataReturn($data)
    {
        return json_encode($data);
    }
}
