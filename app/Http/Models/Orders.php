<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    //订单状态
    const ORDER_NO_MATCH = 0;
    const ORDER_MATCHED = 1;
    //交易状态
    const TRADE_NO_PAY = 2;
    const TRADE_NO_CONFIRM = 3;
    const TRADE_FINISHED = 4;

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
}
