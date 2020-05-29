<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base;
use App\Http\Models\Coins;
use App\Http\Models\Orders;
use App\Http\Models\TradeNumbers;
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
            $filterArray = array_filter($buys,function ($array) use ($id){
                if (array_search($id,$array)) return true;
                return false;
            });
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
            if (!empty($data['account'])) $where['sales_member_phone'] = $data['account'];
            if ($data['number'] != '-1') $where['trade_number'] = $data['number'];
            if ($data['matchStatus'] != '-1') $where['order_status'] = $data['matchStatus'];
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

}
