<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

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

}
