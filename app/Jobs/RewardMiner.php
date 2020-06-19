<?php

namespace App\Jobs;

use App\Http\Models\Activities;
use App\Http\Models\Bills;
use App\Http\Models\FailedJobs;
use App\Http\Models\Members;
use App\Http\Models\Miners;
use App\Http\Models\MyMiners;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RewardMiner implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $member;

    /**
     * Create a new job instance.
     * @param $member
     * @return void
     */
    public function __construct($member)
    {
        $this->member = $member;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //计算团队有效算力
        $subordinates = Members::where('parentid',$this->member->id)->get();
        $hashrate = 0;
        if (!$subordinates->isEmpty()) {
            $myMiner = new MyMiners();
            foreach ($subordinates as $s) {
                //等级烧伤，累计团队算力
                if ($this->member->level_id >= $s->level_id) {
                    $hashrate += $myMiner->hashrateSum($s->id);
                }
            }
        }
        //获得奖励
        $activities = Activities::all();
        foreach ($activities as $activity) {
            $rewardMember = $activity->reward_member;
            if (count($subordinates) >= $activity->subordinate
                && $hashrate >= $activity->hashrate
                && !in_array($this->member->id,$rewardMember)){
                $miner = Miners::find($activity->reward_miner_type);
                for ($i=0;$i<$activity->reward_miner_number;$i++){
                    MyMiners::create([
                        'member_id' => $this->member->id,
                        'miner_id' => $miner->id,
                        'miner_tittle' => $miner->tittle,
                        'runtime' => $miner->runtime,
                        'nph' => $miner->nph,
                        'total_dig' => $miner->total_dig,
                        'hashrate' => $miner->hashrate,
                        'run_status' => MyMiners::RUNNING
                    ]);
                }
                array_push($rewardMember,$this->member->id);
                $activity->reward_member = $rewardMember;
                $activity->save();
                Bills::createBill($this->member->id,$miner->tittle.'-分享奖励','+'.$activity->reward_miner_number);
            }
        }
    }

    public function failed(\Exception $exception)
    {
        FailedJobs::create([
            'queue' => 'give',
            'exception' => $exception->getMessage()
        ]);
    }
}
