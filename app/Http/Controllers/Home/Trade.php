<?php
namespace App\Http\Controllers\Home;

use App\Http\Controllers\Base;
use App\Http\Models\Bills;
use App\Http\Models\Coins;
use App\Http\Models\Members;
use App\Http\Models\MyMiners;
use App\Http\Models\Orders;
use App\Http\Models\SystemSettings;
use App\Http\Models\TradeNumbers;
use App\Jobs\BuyMatch;
use App\Jobs\RewardCoin;
use App\Jobs\SalesMatch;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class Trade extends Base
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->middleware('tradeAuth');
    }

    /**
     * 交易页
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function buy()
    {
        //币价
        $coinPrice = Coins::orderBy('id','desc')->first();
        $viewParams['coinPrice'] = $coinPrice->price;
        //我的买单
        $buyOrders = Cache::get('tradeBuy');
        $buyOrdersArry = [];
        if (!empty($buyOrders)){
            foreach ($buyOrders as $k => $buyOrder) {
                if ($buyOrder['buy_member_id'] == Auth::id() &&
                    $buyOrder['order_status'] == Orders::ORDER_NO_MATCH){
                    array_push($buyOrdersArry,$buyOrder);
                }
            }
        }
        $viewParams['buyOrders'] = $buyOrdersArry;
        //我的卖单
        $salesOrders = Cache::get('tradeSales');
        $salesOrdersArry = [];
        if (!empty($salesOrders)){
            foreach ($salesOrders as $k => $salesOrder) {
                if ($salesOrder['sales_member_id'] == Auth::id() &&
                    $salesOrder['order_status'] == Orders::ORDER_NO_MATCH){
                    array_push($salesOrdersArry,$salesOrder);
                }
            }
        }
        $viewParams['salesOrders'] = $salesOrdersArry;

        //交易规格
        $numbers = TradeNumbers::all();
        $item = [];
        foreach ($numbers as $k => $n){
            $item[$k] = $n->number;
        }
        $viewParams['tradeNumbers'] = $item;

        return view('home.trade.buy',$viewParams);
    }

    /**
     * 交易安全密码验证
     * @return false|string
     */
    public function tradeCheck()
    {
        $safePassword = $this->request->input('password');
        if (!Hash::check($safePassword,Auth::user()->safe_password)){
            return $this->dataReturn(['status'=>1044,'message'=>'密码错误']);
        }
        $this->request->session()->put('safeP',time());
        return $this->dataReturn(['status'=>0,'message'=>'验证成功']);
    }

    /**
     * 买入
     * @return false|string
     */
    public function tradeBuy()
    {
        $data = $this->request->input();
        $member = Auth::user();
        $buyOrders = Cache::get('tradeBuy');
        $n = 0;
        if (!empty($buyOrders)){
            foreach ($buyOrders as $buyOrder) {
                if ($buyOrder['buy_member_id'] == $member->id && $buyOrder['order_status'] == Orders::ORDER_NO_MATCH){
                    $n++;
                }
            }
            if ($n == 5){
                return $this->dataReturn(['status'=>1040,'message'=>'最多挂5单']);
            }
        }
        //加入队列匹配
        BuyMatch::dispatch($data,$member)->onQueue('match');

        return $this->dataReturn(['status'=>0,'message'=>'买入成功']);
    }

    /**
     * 卖出
     * @return false|string
     */
    public function tradeSales()
    {
        $member = Auth::user();
        $data = $this->request->input();

        //信用过低无法卖出
        $creditMin = SystemSettings::getSysSettingValue('low_credit_forbidden_sales');
        if ($member->credit < $creditMin){
            return $this->dataReturn(['status'=>1044,'message'=>'信用过低，请先买入增加信用']);
        }

        //有矿机在运行才能卖出
        $runningNumber = MyMiners::where('member_id',$member->id)->where('run_status',MyMiners::RUNNING)->count();
        if ($runningNumber == 0){
            return $this->dataReturn(['status'=>1043,'message'=>'没有运行的矿机，无法卖出']);
        }

        $assets = Cache::get('assets'.$member->id);
        $handRate = SystemSettings::getSysSettingValue('trade_handling_charge');
        $deductNumber = $data['salesNumber']*(1+$handRate);
        if ($assets->balance < $deductNumber){
            return $this->dataReturn(['status'=>1041,'message'=>'余额不足']);
        }
        //限制卖出次数
        $salesOrders = Cache::get('tradeSales');
        $availableSalesTimes = $member->level->sales_times;
        $salesNumber = 0;
        if (!empty($salesOrders)){
            foreach ($salesOrders as $salesOrder) {
                if ($salesOrder['sales_member_id'] == $member->id && $salesOrder['order_status'] == Orders::ORDER_NO_MATCH){
                    $salesNumber++;
                }
            }
            if ($availableSalesTimes <= $salesNumber){
                return $this->dataReturn(['status'=>1042,'message'=>'每天只能卖出'.$availableSalesTimes.'单']);
            }
        }
        $tradeCount = Orders::where('sales_member_id',$member->id)->where('created_at','>=',date('Y-m-d'))->count();
        if ($availableSalesTimes <= ($tradeCount + $salesNumber)){
            return $this->dataReturn(['status'=>1042,'message'=>'每天只能卖出'.$availableSalesTimes.'单']);
        }
        //临时从余额中扣除币
        $assets->balance -= $deductNumber;
        $assets->blocked_assets += $deductNumber;
        Cache::put('assets'.$member->id,$assets,Carbon::tomorrow());
        //加入卖出队列匹配
        SalesMatch::dispatch($data,$member)->onQueue('match');

        return $this->dataReturn(['status'=>0,'message'=>'委托卖出成功']);
    }

    /**
     * 取消委托单
     * @param $orderId
     * @return string
     */
    public function cancelOrder($orderId)
    {
        if (substr($orderId,0,2) == 'hb'){
            $buyOrders = Cache::get('tradeBuy');
            foreach ($buyOrders as $k => $buyOrder) {
                if ($buyOrder['order_id'] == $orderId && $buyOrder['order_status'] == Orders::ORDER_NO_MATCH){
                    array_splice($buyOrders,$k,1);
                    Cache::put('tradeBuy',$buyOrders,Carbon::tomorrow());
                    return $this->dataReturn(['status'=>0,'message'=>'取消成功']);
                }
            }
        }else {
            $salesOrders = Cache::get('tradeSales');
            foreach ($salesOrders as $k => $salesOrder) {
                if ($salesOrder['order_id'] == $orderId && $salesOrder['order_status'] == Orders::ORDER_NO_MATCH){
                    array_splice($salesOrders,$k,1);
                    Cache::put('tradeSales',$salesOrders,Carbon::tomorrow());
                    //恢复资产
                    $handRate = SystemSettings::getSysSettingValue('trade_handling_charge');
                    $blockedNumber = $salesOrder['trade_number'] * (1+$handRate);
                    $salesAssets = Cache::get('assets'.$salesOrder['sales_member_id']);
                    $salesAssets->balance += $blockedNumber;
                    $salesAssets->blocked_assets -= $blockedNumber;
                    Cache::put('assets'.$salesOrder['sales_member_id'],$salesAssets,Carbon::tomorrow());
                    return $this->dataReturn(['status'=>0,'message'=>'取消成功']);
                }
            }
        }
        return $this->dataReturn(['status'=>-1,'message'=>'取消失败，订单已匹配']);
    }

    /**
     * 交易中心
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tradeCenter()
    {
        //构造币价走势
        $coinPrice = Coins::orderBy('id','desc')->limit(7)->get();
        $coinPriceArry = [];
        foreach ($coinPrice as $k => $p) {
            $coinPriceArry[$k] = $p->price;
        }
        for ($i=count($coinPriceArry);$i<7;$i++){
            $coinPriceArry[$i] = 0;
        }
        sort($coinPriceArry);
        $coinPriceStr = implode(',',$coinPriceArry);
        //排单数量
        $tradeNumber = TradeNumbers::all();
        //买单
        $buyOrders = $this->getBuyOrders(5);

        return view('home.trade.tradeCenter',
            ['coinPrice'=>$coinPriceStr,'tradeNumber'=>$tradeNumber,'buyOrders'=>$buyOrders]);
    }

    /**
     * 排单
     * @param $number
     * @return false|string
     */
    public function paidan($number)
    {
        $buyOrders = $this->getBuyOrders($number);
        if (empty($buyOrders)){
            return $this->dataReturn(['status'=>0,'message'=>'无买单']);
        }else{
            return $this->dataReturn(['status'=>1,'orders'=>$buyOrders]);
        }
    }

    protected function getBuyOrders($number)
    {
        $tradeBuy = Cache::get('tradeBuy');
        $buyOrders = [];
        if (!empty($tradeBuy)){
            foreach ($tradeBuy as $b) {
                if (count($buyOrders) > 50){
                    break;
                }
                if ($b['trade_number'] == $number && $b['order_status'] == Orders::ORDER_NO_MATCH){
                    array_push($buyOrders,$b);
                }
            }
        }
        return $buyOrders;
    }
}
