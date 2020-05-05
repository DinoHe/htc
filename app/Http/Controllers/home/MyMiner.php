<?php
namespace App\Http\Controllers\Home;

use App\Http\Controllers\Base;
use App\Http\Models\MyMiners;
use Illuminate\Support\Facades\Auth;

class MyMiner extends Base
{
    public function running()
    {
        $myMiners = MyMiners::where('member_id',Auth::id())->where('run_status',MyMiners::RUNNING)->get();

        return view('home.myminer.running')->with('myMiners',$myMiners);
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
