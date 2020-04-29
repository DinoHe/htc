<?php
namespace App\Libraries\SMS;

use Illuminate\Support\Facades\Log;
use REST;

class SendTemplateSMS
{
    //主帐号,对应开官网发者主账号下的 ACCOUNT SID
    private $accountSid= '8a216da86b652116016b73afe51c0bb2';

    //主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
    private $accountToken= '6c5376cbe11a4f74a349c4126ae1612d';

    //应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
    //在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID
    private $appId='8a216da86b652116016b73afe5830bb9';

    //请求地址
    //沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com
    //生产环境（用户应用上线使用）：app.cloopen.com
    private $serverIP='app.cloopen.com';


    //请求端口，生产环境和沙盒环境一致
    private $serverPort='8883';

    //REST版本号，在官网文档REST介绍中获得。
    private $softVersion='2013-12-26';


    /**
     * 发送模板短信
    * @param to 手机号码集合,用英文逗号分开
    * @param datas 内容数据 格式为数组 例如：array('Marry','Alon')，如不需替换请填 null
    * @param $tempId 模板Id,测试应用和未上线应用使用测试模板请填写1，正式应用上线后填写已申请审核通过的模板ID
    */
    function sendTemplateSMS($to,$datas,$tempId)
    {
        // 初始化REST SDK
        $rest = new REST($this->serverIP,$this->serverPort,$this->softVersion);
        $rest->setAccount($this->accountSid,$this->accountToken);
        $rest->setAppId($this->appId);

        // 发送模板短信
        // echo "Sending TemplateSMS to $to <br/>";
        $result = $rest->sendTemplateSMS($to,$datas,$tempId);
        if($result == NULL ) {
            Log::error('短信发送错误');
            return json_encode(['status'=>1102,'message'=>'短信发送出错']);
        }
        if($result->statusCode!=0) {
            // echo "error code :" . $result->statusCode . "<br>";
            // echo "error msg :" . $result->statusMsg . "<br>";
            //TODO 添加错误处理逻辑
            Log::error($result);
            return json_encode(['status'=>1101,'message'=>$result->statusCode.',发送失败','describe'=>$result->statusMsg]);
        }else{
            // 获取返回信息
            $smsmessage = $result->TemplateSMS;
            // echo "dateCreated:".$smsmessage->dateCreated."<br/>";
            // echo "smsMessageSid:".$smsmessage->smsMessageSid."<br/>";
            //TODO 添加成功处理逻辑
            return json_encode(['status'=>0,'date'=>$smsmessage->dateCreated,'messageSid'=>$smsmessage->smsMessageSid]);
        }
    }
}

//Demo调用
		//**************************************举例说明***********************************************************************
		//*假设您用测试Demo的APP ID，则需使用默认模板ID 1，发送手机号是13800000000，传入参数为6532和5，则调用方式为           *
		//*result = sendTemplateSMS("13800000000" ,array('6532','5'),"1");																		  *
		//*则13800000000手机号收到的短信内容是：【云通讯】您使用的是云通讯短信模板，您的验证码是6532，请于5分钟内正确输入     *
		//*********************************************************************************************************************
// sendTemplateSMS("",array('',''),"");//手机号码，替换内容数组，模板ID

