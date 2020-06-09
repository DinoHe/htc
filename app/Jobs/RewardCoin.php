<?php

namespace App\Jobs;

use App\Http\Models\Assets;
use App\Http\Models\Bills;
use App\Http\Models\SystemSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RewardCoin implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $tradeNumber;
    protected $leaderId;

    /**
     * Create a new job instance.
     * @param $tradeNumber
     * @param $leaderId
     * @return void
     */
    public function __construct($tradeNumber,$leaderId)
    {
        $this->tradeNumber = $tradeNumber;
        $this->leaderId = $leaderId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $leaderAssets = Assets::where('member_id',$this->leaderId)->first();
        $rewardRate = SystemSettings::getSysSettingValue('subordinate_buy_reward_rate');
        $rewardCoin = $this->tradeNumber * $rewardRate;
        $leaderAssets->balance += $rewardCoin;
        $leaderAssets->save();
        Bills::createBill($this->leaderId,'余额-直推买币奖励','+'.$rewardCoin);
    }
}
