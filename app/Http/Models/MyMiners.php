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
}
