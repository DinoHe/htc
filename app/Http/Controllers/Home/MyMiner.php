<?php
namespace App\Http\Controllers\Home;

use App\Http\Controllers\Base;
use App\Http\Models\Assets;
use App\Http\Models\Bills;
use App\Http\Models\MyMiners;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class MyMiner extends Base
{
    public function running()
    {
        $myMiner = new MyMiners();
        $myMiners = MyMiners::where('member_id',Auth::id())->where('run_status',MyMiners::RUNNING)->get();
        $this->initMiners($myMiners);
        $hashrates = $myMiner->hashrateSum(Auth::id());

        return view('home.myminer.running',['hashrates'=>$hashrates,'myMiners'=>$myMiners]);
    }

    /**
     * 一键收取
     * @return false|string
     */
    public function collect()
    {
        $memberId = Auth::id();
        $c = Cache::get('collect'.$memberId);
        if (!empty($c)){
            return $this->dataReturn(['status'=>1021,'message'=>'今天已收取，请明天再来']);
        }
        $minersInfo = $this->request->input('info');
        $minersInfo = json_decode($minersInfo);
        $collectSum = 0;
        foreach ($minersInfo as $m){
            $collectSum += $m->no_collect;
            $dug = $m->dug + $m->no_collect;
            $data['run_status'] = MyMiners::RUNNING;
            if ($dug >= $m->total_dig){
                $dug = $m->total_dig;
                $data['run_status'] = MyMiners::RUN_FINISHED;
            }
            MyMiners::where('id',$m->id)->update([
                'dug' => $dug,
                'run_status' => $data['run_status']
            ]);
        }
        //更新缓存
        $assetsCache = Cache::get('assets'.$memberId);
        $assetsCache->balance += $collectSum;
        Cache::put('assets'.$memberId,$assetsCache,Carbon::tomorrow());
        Cache::put('collect'.$memberId,time(),Carbon::tomorrow());
        //保存数据到数据库
        Assets::where('member_id',$memberId)->update(['balance'=>$assetsCache->balance*100]);

        Bills::createBill($memberId,'余额-矿机产出','+'.$collectSum);
        return $this->dataReturn(['status'=>0,'message'=>'收取成功']);
    }

    public function finished()
    {
        $myMiners = MyMiners::where('member_id',Auth::id())
            ->where('run_status',MyMiners::RUN_FINISHED)
            ->limit(0,20)
            ->get();

        return view('home.myminer.finished')->with('myMiners',$myMiners);
    }

    public function getMoreMinerFinished($offset)
    {
        $myMiners = MyMiners::where('member_id',Auth::id())
            ->where('run_status',MyMiners::RUN_FINISHED)
            ->limit($offset,20)
            ->get();
        if ($myMiners->isEmpty()){
            return $this->dataReturn(['status'=>-1,'message'=>'没有更多数据']);
        }
        return $this->dataReturn(['status'=>0,'miners'=>$myMiners]);
    }
}
