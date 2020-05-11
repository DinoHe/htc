<?php

namespace App\Jobs;

use App\Events\TradingOrder;
use App\Http\Models\Orders;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class SalesMatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $salesInfo;
    protected $salesMember;

    /**
     * Create a new job instance.
     * @param $salesInfo
     * @param $member
     * @return void
     */
    public function __construct($salesInfo,$member)
    {
        $this->salesInfo = $salesInfo;
        $this->salesMember = $member;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $buyOrders = Cache::get('tradeBuy');
        $buyOrdersArry = [];
        if (!empty($buyOrders)){
            foreach ($buyOrders as $k => $buyOrder) {
                if ($buyOrder['trade_number'] == $this->salesInfo['salesNumber'] &&
                    $buyOrder['buy_member_id'] != $this->salesMember->id){
                    $buyOrder['index'] = $k;
                    array_push($buyOrdersArry,$buyOrder);
                }
            }
            if (!empty($buyOrdersArry)){
                $randIndex = array_rand($buyOrdersArry);
                $buyOrder = $buyOrdersArry[$randIndex];
                $res = Orders::create([
                    'order_id' => 'HT'.time().substr($this->buyMember->phone,8),
                    'buy_member_id' => $buyOrder['buy_member_id'],
                    'buy_member_phone' => $buyOrder['buy_member_phone'],
                    'sales_member_id' => $this->salesMember->id,
                    'sales_member_phone' => $this->salesMember->phone,
                    'trade_number' => $this->salesInfo['salesNumber'],
                    'trade_price' => $this->salesInfo['price'],
                    'trade_total_price' => $this->salesInfo['salesNumber'] * $this->salesInfo['price'],
                    'trade_status' => Orders::TRADE_NO_PAY
                ]);
                if ($res){
                    $index = $buyOrdersArry[$randIndex]['index'];
                    array_splice($buyOrders,$index,1);
                    Cache::put('tradeBuy',$buyOrders,Carbon::tomorrow());
                    return;
                }
            }
        }
        $salesOrders = Cache::get('tradeSales');
        if (empty($salesOrders)){
            $salesOrders = [];
        }
        $tmp = [
            'order_id' => 'hs'.substr($this->salesMember->phone,8).time(),
            'buy_member_id' => $this->salesMember->id,
            'buy_member_phone' => $this->salesMember->phone,
            'trade_number' => $this->salesInfo['salesNumber'],
            'trade_price' => $this->salesInfo['price'],
            'created_at' => date('Y-m-d H:i:s')
        ];
        array_push($salesOrders,$tmp);
        Cache::put('tradeSales',$salesOrders,Carbon::tomorrow());
    }
}
