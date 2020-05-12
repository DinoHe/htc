<?php

namespace App\Jobs;

use App\Http\Models\Orders;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

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
        $salesOrdersArry = [];
        $orderStatus = Orders::ORDER_NO_MATCH;
        if (!empty($salesOrders)){
            foreach ($salesOrders as $k => $salesOrder) {
                if ($salesOrder['trade_number'] == $this->buyInfo['buyNumber'] &&
                    $salesOrder['sales_member_id'] != $this->buyMember->id &&
                    $salesOrder['order_status'] != Orders::ORDER_MATCHED){
                    $salesOrder['index'] = $k;
                    array_push($salesOrdersArry,$salesOrder);
                }
            }
            if (!empty($salesOrdersArry)){
                $randIndex = array_rand($salesOrdersArry);
                $salesOrder = $salesOrdersArry[$randIndex];
                $res = Orders::create([
                    'order_id' => 'HT'.time().substr($this->buyMember->phone,8),
                    'buy_member_id' => $this->buyMember->id,
                    'buy_member_phone' => $this->buyMember->phone,
                    'sales_member_id' => $salesOrder['sales_member_id'],
                    'sales_member_phone' => $salesOrder['sales_member_phone'],
                    'trade_number' => $this->buyInfo['buyNumber'],
                    'trade_price' => $this->buyInfo['price'],
                    'trade_total_price' => $this->buyInfo['buyNumber'] * $this->buyInfo['price'],
                    'trade_status' => Orders::TRADE_NO_PAY
                ]);
                if ($res){
                    $index = $salesOrdersArry[$randIndex]['index'];
                    $salesOrders[$index]['order_status'] = Orders::ORDER_MATCHED;
                    $orderStatus = Orders::ORDER_MATCHED;
                    Cache::put('tradeSales',$salesOrders,Carbon::tomorrow());
                }
            }
        }
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
            'order_status' => $orderStatus,
            'created_at' => date('Y-m-d H:i:s')
        ];
        array_push($buyOrders,$tmp);
        Cache::put('tradeBuy',$buyOrders,Carbon::tomorrow());
    }
}
