<?php
namespace App\Http\Controllers\Home;

use App\Http\Controllers\Base;
use App\Http\Models\SystemSettings;
use App\Http\Models\TradeNumbers;
use App\Jobs\BuyMatch;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class Trade extends Base
{
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
//        dd($buyOrders);
        $message['buyOrders'] = $buyOrders;
        // 卖单
        $salesOrders = Cache::get('tradeSales');
        $message['salesOrders'] = $salesOrders;

        return view('home.trade.buy',$message);
    }

    public function tradeBuy()
    {
        $data = $this->request->input();
        $member = Auth::user();
        $buyOrders = Cache::get('tradeBuy');
        $n = 0;
        if (!empty($buyOrders)){
            foreach ($buyOrders as $buyOrder) {
                if ($buyOrder['memberId'] == $member->id){
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

    public function tradeSales()
    {

    }

    public function unprocessedOrder()
    {
        return view('home.trade.unprocessedOrder');
    }

    public function orderPreview()
    {
        return view('home.trade.orderPreview');
    }

    public function uploadPayImg()
    {
        $file = $this->request->file('pay_img');
        if (empty($file)) return back()->withErrors(['uploadError'=>'请选择要上传的截图'])->withInput();
        $res = $file->store('public/payImg');
        if (empty($res)){
            return back()->withErrors(['uploadError'=>'上传失败，请稍后重新上传'])->withInput();
        }
        return redirect('home/unprocessedOrder');
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
