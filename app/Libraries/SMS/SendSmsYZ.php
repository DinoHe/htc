<?php


namespace App\Libraries\SMS;


class SendSmsYZ
{
    public function sendSms($mobile,$templateParam='',$captchaTemplate=true)
    {
        $accountsid = '595f802fc241af69a36cfffa4580a35a';
        $token = '3de2a4d54a974382ee075f6c27c54473';
        $appid = "1ae2029376574b0ab31d3534a99a1224";	//应用的ID，可在开发者控制台内的短信产品下查看
//        $templateParam = rand(1001,9897); //多个参数使用英文逗号隔开（如：param=“a,b,c”），如为参数则留空
//        $mobile = '13048814716';
        if ($captchaTemplate){
            $templateId = '554288'; //验证码模板
        }else{
            $templateId = '554289'; //通知模板
        }
        $options = ['accountsid'=>$accountsid,'token'=>$token];

        $ucpass = new Ucpaas($options);
        return $ucpass->SendSms($appid,$templateId,$templateParam,$mobile);
    }
}
