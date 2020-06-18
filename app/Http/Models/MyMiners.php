<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class MyMiners extends Model
{

//    运行状态
    const RUNNING = 0;
    const RUN_FINISHED = 1;
    const RUN_EXPIRED = 2;

    protected $fillable = [
        'member_id','miner_id','miner_tittle','dug','runtime','nph','total_dig','hashrate','run_status'
    ];

    public function member()
    {
        return $this->belongsTo('App\Http\Models\Members','member_id');
    }

    public function getMinerStatus($status)
    {
        switch ($status){
            case self::RUNNING:
                return '运行中';
            case self::RUN_FINISHED:
                return '已结束';
            case self::RUN_EXPIRED:
                return '已过期';
        }
    }

    /**
     * 会员算力统计
     * @param $memberId
     * @return float
     */
    public function hashrateSum($memberId)
    {
        $hashrateSum = self::where('member_id',$memberId)->where('run_status',self::RUNNING)->sum('hashrate');
        return round($hashrateSum,2);
    }
}
