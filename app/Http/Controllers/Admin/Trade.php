<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base;
use App\Http\Models\Coins;
use App\Http\Models\Members;
use App\Http\Models\Orders;
use App\Http\Models\TradeNumbers;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class Trade extends Base
{
    public function buyList()
    {
        $buys = Cache::get('tradeBuy')?:[];
        $numbers = TradeNumbers::all();
        if ($this->request->isMethod('post') && !empty($buys)){
            $data = $this->request->input();
            $this->request->flashOnly(['account']);
            $buySearch = [];
            $where = [];
            if (!empty($data['account'])) $where['buy_member_phone'] = $data['account'];
            if ($data['number'] != '-1') $where['trade_number'] = $data['number'];
            if ($data['matchStatus'] != '-1') $where['order_status'] = $data['matchStatus'];
            if (!empty($where)){
                foreach ($buys as $buy) {
                    $assoc = array_intersect_assoc($buy,$where);
                    if (!empty($assoc) && count($assoc) == count($where)){
                        array_push($buySearch,$buy);
                    }
                }
                $buys = $buySearch;
            }
        }
        return view('admin.trade.buy-list',['buys'=>$buys,'numbers'=>$numbers]);
    }

    public function buyAdd()
    {
        if ($this->request->isMethod('post')){
            $data = $this->request->input();
            $buys = Cache::get('tradeBuy');
            if (empty($buys)){
                $buys = [];
            }
            for ($i=0;$i<$data['orderNumber'];$i++){
                $tmp = [
                    'order_id' => time().$i,
                    'buy_member_id' => -1,
                    'buy_member_phone' => $data['account'],
                    'trade_number' => $data['number'],
                    'trade_price' => $data['price'],
                    'order_status' => Orders::ORDER_NO_MATCH,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                array_push($buys,$tmp);
            }
            Cache::forever('tradeBuy',$buys);
            return $this->dataReturn(['status'=>0,'message'=>'添加成功']);
        }
        $numbers = TradeNumbers::all();
        $price = Coins::orderBy('id','desc')->first()->price;
        return view('admin.trade.buy-add',['numbers'=>$numbers,'price'=>$price]);
    }

    public function buyDestroy()
    {
        $id = $this->request->input('id');
        $ids = explode(',',$id)?:[id];
        $buys = Cache::get('tradeBuy');
        foreach ($ids as $id) {
            $filterArray = parent::array2Filter($id,$buys);
            if (!empty($filterArray)){
                array_splice($buys,array_search($filterArray,$buys),1);
            }
        }
        Cache::forever('tradeBuy',$buys);
    }

    public function buyClear()
    {
        Cache::forget('tradeBuy');
    }

    public function salesList()
    {
        $sales = Cache::get('tradeSales')?:[];
        $numbers = TradeNumbers::all();
        if ($this->request->isMethod('post') && !empty($sales)){
            $data = $this->request->input();
            $this->request->flashOnly(['account']);
            $salesSearch = [];
            $where = [];
            if ($data['matchStatus'] != '-1') $where['order_status'] = $data['matchStatus'];
            if ($data['number'] != '-1') $where['trade_number'] = $data['number'];
            if (!empty($data['account'])) $where['sales_member_phone'] = $data['account'];
            foreach ($sales as $sale) {
                $assoc = array_intersect_assoc($sale,$where);
                if (!empty($assoc) && count($assoc) == count($where)){
                    array_push($salesSearch,$sale);
                }
            }
            if (!empty($saleSearch)) $sales = $saleSearch;
        }
        return view('admin.trade.sales-list',['sales'=>$sales,'numbers'=>$numbers]);
    }

    public function salesDestroy()
    {
        $id = $this->request->input('id');
        $ids = explode(',',$id)?:[id];
        $sales = Cache::get('tradeSales');
        foreach ($ids as $id) {
            $filterArray = parent::array2Filter($id,$sales);
            if (!empty($filterArray)){
                array_splice($sales,array_search($filterArray,$sales),1);
            }
        }
        Cache::forever('tradeSales',$sales);
    }

    public function salesClear()
    {
        Cache::forget('tradeSales');
    }

    public function order()
    {
        if ($this->request->isMethod('post')){
            $data = $this->request->input();
            $this->request->flashOnly(['tradeStatus','date_start','date_end','account']);
            $model = Orders::where(null);
            if ($data['tradeStatus'] != '-1'){
                $model = $model->where('trade_status',$data['tradeStatus']);
            }
            $model = $model->WhereBetween('created_at',[$data['date_start'],$data['date_end']]);
            $orders = $model->cursor();
            if (!empty($data['account'])) {
                $orders = $orders->filter(function ($order) use ($data){
                    return $order->buy_member_phone == $data['account'] || $order->sales_member_phone == $data['account'];
                });
            }
            return view('admin.trade.order',['orders'=>$orders]);
        }
        $orders = Orders::where('trade_status',Orders::TRADE_NO_PAY)
            ->orWhere('trade_status',Orders::TRADE_NO_CONFIRM)->get();
        return view('admin.trade.order',['orders'=>$orders]);
    }

    public function orderCancelEdit()
    {
        $data = $this->request->input();
        $order = Orders::where('id',$data['orderId'])->first();
        if ($data['cancelType'] == 'blockBuy'){
            $buy = Members::where('id',$order->buy_member_id)->first();
            $buy->activated = Members::BLOCKED_TMP;
            $buy->credit -= 6;
            $buy->save();
            //强制退出登录
            Cache::put('blocked'.$order->buy_member_id,time(),Carbon::now()->addHours(2));
        }elseif ($data['cancelType'] == 'blockSales'){
            $sales = Members::where('id',$order->sales_member_id)->first();
            $sales->activated = Members::BLOCKED_TMP;
            $sales->credit -= 6;
            $sales->save();
            Cache::put('blocked'.$order->sales_member_id,time(),Carbon::now()->addHours(2));
        }
        //取消交易，归还已冻结的卖家资产
        $order->cancelTrade($order);

        return $this->dataReturn(['status'=>0,'message'=>'操作成功']);
    }

    public function coinList()
    {
        $coins = Coins::all();
        return view('admin.trade.coin-list',['coins'=>$coins]);
    }

    public function coinAdd()
    {
        if ($this->request->isMethod('post')){
            Coins::create(['price'=>$this->request->input('price')]);
            return $this->dataReturn(['status'=>0,'message'=>'添加成功']);
        }
        return view('admin.trade.coin-add');
    }

    public function coinEdit()
    {
        $data = $this->request->input();
        if ($this->request->isMethod('post')){
            Coins::where('id',$data['id'])->update(['price'=>$data['price']]);
            return $this->dataReturn(['status'=>0,'message'=>'修改成功']);
        }
        $coin = Coins::find($data['id']);
        return view('admin.trade.coin-edit',['coin'=>$coin]);
    }

    public function coinDestroy()
    {
        $id = $this->request->input('id');
        $ids = explode(',',$id)?:$id;
        Coins::destroy($ids);
        return $this->dataReturn(['status'=>0,'message'=>'删除成功']);
    }

}
