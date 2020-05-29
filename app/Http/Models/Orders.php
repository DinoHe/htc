<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Self_;

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
        'trade_price','trade_total_money','payment_img','trade_status'
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
        }
    }

}
