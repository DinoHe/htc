<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base;
use App\Http\Models\Coins;
use App\Http\Models\Members;
use App\Http\Models\Orders;
use App\Http\Models\TradeNumbers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use phpDocumentor\Reflection\Types\Boolean;

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
            foreach ($buys as $buy) {
                $assoc = array_intersect_assoc($buy,$where);
                if (!empty($assoc) && count($assoc) == count($where)){
                    array_push($buySearch,$buy);
                }
            }
            if (!empty($buySearch)) $buys = $buySearch;
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
            $filterArray = $this->arrayFilter($id,$buys);
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
            $filterArray = $this->arrayFilter($id,$sales);
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
        $id = $this->request->input();
        $res = Members::where('id',$id['memberId'])->update(['activated'=>Members::BLOCKED_TMP]);
        if ($res){
            $s = $this->request->session()->all();
            $this->request->session()->remove(array_search($id['memberId'],$s));
            Orders::where('id',$id['orderId'])->update(['trade_status'=>Orders::TRADE_CANCEL]);
        }
        return $this->dataReturn(['status'=>0,'message'=>'操作成功']);
    }

    private function arrayFilter($obj,$array):array
    {
        return array_filter($array,function ($array) use ($obj){
            if (array_search($obj,$array)) return true;
            return false;
        });
    }

}
