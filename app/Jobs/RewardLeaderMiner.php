<?php

namespace App\Jobs;

use App\Http\Models\Bills;
use App\Http\Models\BuyActivities;
use App\Http\Models\Miners;
use App\Http\Models\MyMiners;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RewardLeaderMiner implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $buyTotal;
    protected $leaderId;

    /**
     * Create a new job instance.
     * @param $buyTotal
     * @param $leaderId
     * @return void
     */
    public function __construct($buyTotal,$leaderId)
    {
        $this->buyTotal = $buyTotal;
        $this->leaderId = $leaderId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $activities = BuyActivities::all();
        foreach ($activities as $activity) {
            if ($this->buyTotal >= $activity->buy_number) {
                $rewardMembers = $activity->reward_member;
                if(!in_array($this->leaderId,$rewardMembers)){
                    $miner = Miners::where('id',$activity->reward_leader_miner_type)->first();
                    for ($i = 0; $i < $activity->reward_leader_miner_number; $i++){
                        MyMiners::create([
                            'member_id' => $this->leaderId,
                            'miner_id' => $miner->id,
                            'miner_tittle' => $miner->tittle,
                            'total_dig' => $miner->total_dig,
                            'nph' => $miner->nph,
                            'runtime' => $miner->runtime,
                            'hashrate' => $miner->hashrate
                        ]);
                    }
                    array_push($rewardMembers,$this->leaderId);
                    $activity->reward_member = $rewardMembers;
                    $activity->save();
                    Bills::createBill($this->leaderId,$miner->tittle.'-下级买币奖励',
                        '+'.$activity->reward_leader_miner_number);
                }else{
                    break;
                }
            }else{
                break;
            }
        }
    }
}
