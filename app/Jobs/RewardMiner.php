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
use Illuminate\Support\Facades\Log;

class RewardMiner implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;
    protected $level;

    /**
     * Create a new job instance.
     * @param $id
     * @param $level
     * @return void
     */
    public function __construct($id,$level)
    {
        $this->id = $id;
        $this->level = $level;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Log::info('---reward---');
            $subordinates = Members::where('parentid',$this->id)->get();
            if (!$subordinates->isEmpty()){
                $hashrate = 0;
                $myMiner = new MyMiners();
                foreach ($subordinates as $s) {
                    //等级烧伤，累计团队算力
                    if ($this->level >= $s->level_id){
                        $hashrate += $myMiner->hashrateSum($s->member_id);
                    }
                }
                $activities = Activities::all();
                Log::info('hashrate: '.$hashrate);
                foreach ($activities as $activity) {
                    $rewardMember = $activity->reward_member;
                    if (count($subordinates) >= $activity->subordinate
                        && $hashrate >= $activity->hashrate
                        && !in_array($this->id,$rewardMember)){
                        Log::info('rewardMiner: '.$activity->reward_miner_type);
                        $miner = Miners::find($activity->reward_miner_type);
                        for ($i=0;$i<$activity->reward_miner_number;$i++){
                            MyMiners::create([
                                'member_id' => $this->id,
                                'miner_id' => $miner->id,
                                'miner_tittle' => $miner->tittle,
                                'runtime' => $miner->runtime,
                                'nph' => $miner->nph,
                                'total_dig' => $miner->total_dig,
                                'hashrate' => $miner->hashrate,
                                'run_status' => MyMiners::RUNNING
                            ]);
                        }
                        array_push($rewardMember,$this->id);
                        $activity->reward_member = $rewardMember;
                        $activity->save();
                        Bills::createBill($this->id,$miner->tittle.'-推广奖励','+'.$activity->reward_miner_number);
                    }
                }
            }
        }catch (\Exception $exception){
            FailedJobs::create([
                'queue' => 'give',
                'exception' => $exception->getMessage()
            ]);
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
