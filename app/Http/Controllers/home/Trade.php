<?php
namespace App\Http\Controllers\Home;

use App\Http\Controllers\Base;
use App\Http\Models\Bills;
use App\Http\Models\Orders;
use App\Http\Models\SystemSettings;
use App\Http\Models\TradeNumbers;
use App\Jobs\BuyMatch;
use App\Jobs\SalesMatch;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Trade extends Base
{
    /**
     * 交易页
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function buy()
    {
        $message['message'] = 'on';
        $openTrade = SystemSettings::getSysSettingValue('trade_open');
        if ($openTrade == 'off'){
            $message['message'] = '临时暂停交易';
        }else{
            $start = SystemSettings::getSysSettingValue('trade_start');
            $end = SystemSettings::getSysSettingValue('trade_end');
            if (date('H') < $start || date('H') > $end){
                $message['message'] = '交易时间：'.$start.':00 - '.$end.':00';
            }
            $numbers = TradeNumbers::all();
            $item = [];
            foreach ($numbers as $k => $n){
                $item[$k] = $n->number;
            }
            $message['tradeNumbers'] = $item;
        }
        //买单
        $buyOrders = Cache::get('tradeBuy');
        if (!empty($buyOrders)){
            foreach ($buyOrders as $k => $buyOrder) {
                if ($buyOrder['buy_member_id'] != Auth::id() || $buyOrder['order_status'] == Orders::ORDER_MATCHED){
                    array_splice($buyOrders,$k,1);
                }
            }
        }
        $message['buyOrders'] = $buyOrders;
        // 卖单
        $salesOrders = Cache::get('tradeSales');
        if (!empty($salesOrders)){
            foreach ($salesOrders as $k => $salesOrder) {
                if ($salesOrder['buy_member_id'] != Auth::id() || $salesOrder['order_status'] == Orders::ORDER_MATCHED){
                    array_splice($salesOrders,$k,1);
                }
            }
        }
        $message['salesOrders'] = $salesOrders;

        return view('home.trade.buy',$message);
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
                if ($buyOrder['buy_member_id'] == $member->id){
                    $n++;
                }
            }
            if ($n == 5){
                return $this->dataReturn(['status'=>1040,'message'=>'最多买入5单']);
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
        $assets = Cache::get('assets'.$member->id);
        $handRate = SystemSettings::getSysSettingValue('trade_handling_charge');
        $deductNumber = $data['salesNumber']*(1+$handRate);
        if ($assets->balance < $deductNumber){
            return $this->dataReturn(['status'=>1041,'message'=>'余额不足']);
        }
        $assets->balance -= $deductNumber;
        $assets->blocked_assets += $deductNumber;
        //加入卖出队列
        SalesMatch::dispatch($data,$member)->onQueue('match');

        return $this->dataReturn(['status'=>0,'message'=>'卖出成功']);
    }

    /**
     * 待处理的订单
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function unprocessedOrder()
    {
        $unOrders = Orders::where('trade_status','<>',Orders::TRADE_FINISHED)->cursor()
            ->filter(function ($orders){
                return $orders->buy_member_id == Auth::id() || $orders->sales_member_id == Auth::id();
            });

        return view('home.trade.unprocessedOrder')->with('unOrders',$unOrders);
    }

    /**
     * 订单详情
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function orderPreview($id)
    {
        $previews = Orders::where('id',$id)->first();
        //买家信息
        $buyMember = $previews->buyMember;
        $previews->buyMemberCredit = $buyMember->credit;
        $previews->buyMemberPhone = $buyMember->phone;
        $previews->buyMemberW = $buyMember->realNameAuth->weixin;
        //卖家信息
        $salesMember = $previews->salesMember;
        $salesMemberRealNameAuth = $salesMember->realNameAuth;
        $previews->salesMemberName = $salesMemberRealNameAuth->name;
        $previews->salesMemberCredit = $salesMember->credit;
        $previews->salesMemberAlipay = $salesMember->phone;
        $previews->salesMemberBankName = $salesMemberRealNameAuth->bank_name;
        $previews->salesMemberBankCard = $salesMemberRealNameAuth->bank_card;
        $previews->salesMemberW = $salesMemberRealNameAuth->weixin;
        $d = 2*3600 - (time() - date_timestamp_get(date_create($previews->updated_at)));
        $previews->h = (int)($d/3600) > 0?:0;
        $previews->i = (int)($d/60%60) > 0?:0;
        $previews->s = $d%60 > 0?:0;

        return view('home.trade.orderPreview')->with('previews',$previews);
    }

    /**
     * 完成付款，上传截图
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function finishPay()
    {
        $file = $this->request->file('pay_img');
        if (empty($file)) return back()->withErrors(['tradeError'=>'请选择要上传的截图'])->withInput();
        if ($file->getSize()/(1024*1024) > 1) return back()->withErrors(['uploadError'=>'请上传小于1M的截图'])->withInput();
        $path = $file->store('public/payImg');
        if (empty($path)){
            return back()->withErrors(['tradeError'=>'上传失败，请稍后重新上传'])->withInput();
        }else{
            $res = Orders::where('id',$this->request->input('id'))->update([
                'payment_img' => substr($path,6),
                'trade_status' => Orders::TRADE_NO_CONFIRM
            ]);
            if ($res){
                return redirect('home/unprocessedOrder');
            }
        }
        return back()->withErrors(['tradeError'=>'系统错误'])->withInput();
    }

    /**
     * 交易确认
     */
    public function finishPayConfirm()
    {
        $order = Orders::where('id',$this->request->input('id'))->first();
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
        Cache::put('assets'.$order->buy_member_id,$buyAssets,Carbon::tomorrow());
        Cache::put('assets'.$order->sales_member_id,$buyAssets,Carbon::tomorrow());
        Bills::createBill($order->buy_member_id,'余额-买入','+'.$order->trade_number);
        Bills::createBill($order->sales_member_id,'余额-卖出','-'.$order->trade_number);
    }

    public function record()
    {
        return view('home.trade.record');
    }

    public function tradeCenter()
    {
        return view('home.trade.tradeCenter');
    }
}
