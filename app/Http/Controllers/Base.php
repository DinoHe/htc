<?php
namespace App\Http\Controllers;

use App\Http\Models\Members;
use App\Libraries\SMS\SendTemplateSMS;
use App\Http\Models\PhoneTmps;
use Illuminate\Http\Request;

class Base
{
    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
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

    public function dataReturn($data)
    {
        return json_encode($data);
    }
}
