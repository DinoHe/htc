<?php


namespace App\Http\Controllers\Home;

use App\Http\Controllers\Base;
use App\Http\Models\Assets;
use App\Http\Models\Miners;
use App\Http\Models\MyMiners;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class Index extends Base
{
    public function index()
    {
        $miners = Miners::all();
        return view('home.index')->with('miners',$miners);
    }

    public function qiandao()
    {
        return $this->dataReturn(['status'=>0,'message'=>'签到成功']);
    }

    public function rent()
    {
        $data = $this->request->input();
        $memberId = Auth::id();
        $assets = Cache::get('assets'.$memberId);
        if ($data['coin_number'] > $assets->balance){
            return $this->dataReturn(['status'=>1030,'message'=>'余额不足']);
        }
        $res = MyMiners::create([
           'member_id' => $memberId,
            'miner_tittle' => $data['miner_tittle'],
            'total_dig' => $data['total_dig'],
            'hashrate' => $data['hashrate'],
            'nph' => $data['nph'],
            'runtime' => $data['runtime'],
            'remaining_time' => $data['runtime'],
            'run_status' => MyMiners::RUNNING
        ]);
        if ($res){
            $balance = $assets->balance*100 - $data['coin_number']*100;
            $update = Assets::where('member_id',$memberId)->update(['balance'=>$balance]);
            if ($update){
                $assets->balance = $balance;
                Cache::put('assets'.$memberId,$assets,Carbon::tomorrow());
            }
        }
        return $this->dataReturn(['status'=>0,'message'=>'租用成功']);
    }
}
