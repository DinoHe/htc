<?php

namespace App\Jobs;

use App\Http\Models\Orders;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class BuyMatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $buyInfo;
    protected $buyMember;

    /**
     * Create a new job instance.
     * @param $buyInfo
     * @param $member
     * @return void
     */
    public function __construct($buyInfo,$member)
    {
        $this->buyInfo = $buyInfo;
        $this->buyMember = $member;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $salesOrders = Cache::get('tradeSales');
        if (!empty($salesOrders)){
            $salesOrder = $salesOrders[array_rand($salesOrders)];
            Orders::create([
                'order_id' => $salesOrder['order_id'],
                'buy_member_id' => $this->buyMember->id,
                'buy_member_phone' => $this->buyMember->phone,
                'sales_member_id' => $salesOrder['sales_member_id'],
                'sales_member_phone' => $salesOrder['sales_member_phone'],
                'trade_number' => $this->buyInfo['buyNumber'],
                'trade_price' => $this->buyInfo['price'],
                'trade_total_price' => $this->buyInfo['tradeNumber'] * ($this->buyInfo['price'] * 100) / 100,
                'trade_status' => Orders::TRADE_NO_PAY
            ]);
        }else {
            $buyOrders = Cache::get('tradeBuy');
            if (empty($buyOrders)){
                $buyOrders = [];
            }
            $tmp = [
                'order_id' => 'hb'.substr($this->buyMember->phone,8).time(),
                'buy_member_id' => $this->buyMember->id,
                'buy_member_phone' => $this->buyMember->phone,
                'trade_number' => $this->buyInfo['buyNumber'],
                'trade_price' => $this->buyInfo['price'],
                'created_at' => date('Y-m-d H:i:s'),
                'order_status' => Orders::ORDER_NO_MATCH
            ];
            array_push($buyOrders,$tmp);
            Cache::put('tradeBuy',$buyOrders,Carbon::tomorrow());
        }
    }
}
