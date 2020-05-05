<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Assets extends Model
{

    protected $fillable = [
        'member_id','balance','miner_wallet','blocked_assets','buy_total'
    ];

    public function getBalanceAttribute($value)
    {
        return $value/100;
    }

    public function getBlockedAssetsAttribute($value)
    {
        return $value/100;
    }

}
