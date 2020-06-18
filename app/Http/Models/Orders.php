<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class Orders extends Model
{
    //交易状态
    const TRADE_NO_PAY = 0;
    const TRADE_NO_CONFIRM = 1;
    const TRADE_FINISHED = 2;
    const TRADE_CANCEL = 3;
    //买卖单状态
    const ORDER_MATCHED = 4;
    const ORDER_NO_MATCH = 5;

    protected $fillable = [
        'order_id','buy_member_id','buy_member_phone','sales_member_id','sales_member_phone','trade_number',
        'trade_price','trade_total_money','payment_img','trade_status','describes'
    ];

    public function buyMember()
    {
        return $this->belongsTo('App\Http\Models\Members','buy_member_id');
    }

    public function salesMember()
    {
        return $this->belongsTo('App\Http\Models\Members','sales_member_id');
    }

    public function getTradeStatus($status)
    {
        switch ($status){
            case self::TRADE_NO_PAY:
                return '待支付';
            case self::TRADE_NO_CONFIRM:
                return '待确认';
            case self::TRADE_FINISHED:
                return '交易完成';
            case self::ORDER_MATCHED:
                return '已匹配';
            case self::ORDER_NO_MATCH:
                return '待匹配';
            case self::TRADE_CANCEL:
                return '交易取消';
        }
    }

    /**
     * 剩余时间计算
     * @param $datetime
     * @return array
     */
    public function remainingTime($datetime):array
    {
        $d = 2*3600 - (time() - date_timestamp_get(date_create($datetime)));
        $remainingTime['h'] = (int)($d/3600) > 0?(int)($d/3600):0;
        $remainingTime['i'] = (int)($d/60%60) > 0?(int)($d/60%60):0;
        $remainingTime['s'] = $d%60 > 0?$d%60:0;
        return $remainingTime;
    }

    public function cancelTrade($order)
    {
        $buys = Cache::get('tradeBuy');
        foreach ($buys as $k => $buy) {
            if ($buy['buy_member_id'] == $order->buy_member_id){
                array_splice($buys,$k,1);
                Cache::put('tradeBuy',$buys,Carbon::tomorrow());
                break;
            }
        }
        $sales = Cache::get('tradeSales');
        foreach ($sales as $k => $sale) {
            if ($sale['sales_member_id'] == $order->sales_member_id){
                array_splice($sales,$k,1);
                Cache::put('tradeSales',$sales,Carbon::tomorrow());
                break;
            }
        }

        $saleAssets = Cache::get('assets'.$order->sales_member_id);
        $handRate = SystemSettings::getSysSettingValue('trade_handling_charge');
        $a = $order->trade_number * (1 + $handRate);
        $saleAssets->balance += $a;
        $saleAssets->blocked_assets -= $a;
        Cache::put('assets'.$order->sales_member_id,$saleAssets,Carbon::tomorrow());

        $order->trade_status = self::TRADE_CANCEL;
        $order->save();
    }

    public function setTradePriceAttribute($value)
    {
        $this->attributes['trade_price'] = $value * 100;
    }

    public function getTradePriceAttribute($value)
    {
        return $value / 100;
    }

    public function setTradeTotalMoneyAttribute($value)
    {
        $this->attributes['trade_total_money'] = $value * 100;
    }

    public function getTradeTotalMoneyAttribute($value)
    {
        return $value / 100;
    }

}
