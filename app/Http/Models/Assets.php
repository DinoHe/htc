<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Assets extends Model
{

    protected $fillable = [
        'member_id','balance','blocked_assets','rewards','buys'
    ];

    public function member()
    {
        return $this->belongsTo('App\Http\Models\Members','member_id');
    }

    public function setBalanceAttribute($value)
    {
        $this->attributes['balance'] = $value * 100;
    }

    public function getBalanceAttribute($value)
    {
        return $value / 100;
    }

    public function setBlockedAssetsAttribute($value)
    {
        $this->attributes['blocked_assets'] = $value * 100;
    }

    public function getBlockedAssetsAttribute($value)
    {
        return $value / 100;
    }

    public function setRewardsAttribute($value)
    {
        $this->attributes['rewards'] = $value * 100;
    }

    public function getRewardsAttribute($value)
    {
        return $value / 100;
    }

    public function setBuysAttribute($value)
    {
        $this->attributes['buys'] = $value * 100;
    }

    public function getBuysAttribute($value)
    {
        return $value / 100;
    }

}
