<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Assets extends Model
{

    protected $fillable = [
        'member_id','balance','blocked_assets','buy_total'
    ];

    public function setBalanceAttribute($value)
    {
        return $value*100;
    }

    public function getBalanceAttribute($value)
    {
        return $value/100;
    }

    public function setBlockedAssetsAttribute($value)
    {
        return $value*100;
    }

    public function getBlockedAssetsAttribute($value)
    {
        return $value/100;
    }

}
