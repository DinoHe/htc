<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Orders extends Model
{
    //交易状态
    const TRADE_NO_PAY = 0;
    const TRADE_NO_CONFIRM = 1;
    const TRADE_FINISHED = 2;
    //买卖单状态
    const ORDER_MATCHED = 3;
    const ORDER_NO_MATCH = 4;

    protected $fillable = [
        'order_id','buy_member_id','buy_member_phone','sales_member_id','sales_member_phone','trade_number',
        'trade_price','trade_total_price','payment_img','trade_status'
    ];

    public function buyMember()
    {
        return $this->belongsTo('App\Http\Models\Members','buy_member_id');
    }

    public function salesMember()
    {
        return $this->belongsTo('App\Http\Models\Members','sales_member_id');
    }

    public function finishPayConfirm($orderId)
    {
        $order = self::where('id',$orderId)->first();
        $buyAssets = Cache::get('assets'.$order->buy_member_id);
        $salesAssets = Cache::get('assets'.$order->sales_member_id);
        DB::beginTransaction();
        //买家资产确认
        $buyAssets->balance += $order->trade_number;
        $buyAssets->buy_total += $order->trade_number;
        //卖家资产确认
        $handRate = SystemSettings::getSysSettingValue('trade_handling_charge');
        $salesAssets->blocked_assets -= $order->trade_number*(1+$handRate);
        //订单完成交易
        $order->trade_status = Orders::TRADE_FINISHED;

        $orderRes = $order->save();
        $buyRes = $buyAssets->save();
        $salesRes = $salesAssets->save();

        if (!$orderRes || !$buyRes || !$salesRes) {
            DB::rollBack();
            return back()->withErrors(['tradeError'=>'系统错误'])->withInput();
        }
        DB::commit();
        $tradeSales = Cache::get('tradeSales');
        foreach ($tradeSales as $k => $tradeSale) {
            if ($tradeSale['sales_member_id'] == $order->sales_member_id){
                array_splice($tradeSales,$k,1);
                break;
            }
        }
        Cache::put('tradeSales',$tradeSales,Carbon::tomorrow());
        Cache::put('assets'.$order->buy_member_id,$buyAssets,Carbon::tomorrow());
        Cache::put('assets'.$order->sales_member_id,$buyAssets,Carbon::tomorrow());
        Bills::createBill($order->buy_member_id,'余额-买入','+'.$order->trade_number);
        Bills::createBill($order->sales_member_id,'余额-卖出','-'.$order->trade_number);
    }
}
