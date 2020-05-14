<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class BuyActivities extends Model
{

    protected $fillable = [
        'buy_number','reward_leader_miner_type','reward_leader_miner_number','reward_member'
    ];

    public function setRewardMemberAttribute($memberIdArray)
    {
        if (count($memberIdArray) > 1){
            $members = implode(',',$memberIdArray);
        }else{
            $members = $memberIdArray[0];
        }
        $this->attributes['reward_member'] = $members;
    }

    public function getRewardMemberAttribute($memberIds)
    {
        $memberIdsArray = [];
        if (!empty($memberIds)){
            $memberIdsArray = explode(',',$memberIds)?:array($memberIds);
        }

        return $memberIdsArray;
    }
}
