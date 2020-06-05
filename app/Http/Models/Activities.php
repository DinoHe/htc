<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Activities extends Model
{

    protected $fillable = [
        'subordinate','hashrate','reward_miner_type','reward_miner_number','reward_member'
    ];

    public function miner()
    {
        return $this->belongsTo('App\Http\Models\Miners','reward_miner_type');
    }

    public function setHashrateAttribute($hashrate)
    {
        $this->attributes['hashrate'] = $hashrate * 10;
    }

    public function getHashrateAttribute($hashrate)
    {
        return $hashrate / 10;
    }

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
