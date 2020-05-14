<?php

namespace App\Http\Middleware;

use App\Http\Models\RealNameAuths;
use App\Http\Models\SystemSettings;
use Closure;

class TradeAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $openTrade = SystemSettings::getSysSettingValue('trade_open');
        $auth = new RealNameAuths();
        if (!$auth->realNameAuthCheck()){
            view()->share('realNameAuth','请先完成实名认证');
        }elseif ($openTrade == 'off'){
            view()->share('trade','临时暂停交易');
        }else{
            $start = SystemSettings::getSysSettingValue('trade_start');
            $end = SystemSettings::getSysSettingValue('trade_end');
            if (date('H') < $start || date('H') > $end){
                view()->share('trade','交易时间：'.$start.':00 - '.$end.':00');
            }
        }

        return $next($request);
    }
}
