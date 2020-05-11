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
        $this->attributes['balance'] = $value*100;
    }

    public function getBalanceAttribute($value)
    {
        return $value/100;
    }

    public function setBlockedAssetsAttribute($value)
    {
        $this->attributes['blocked_assets'] = $value*100;
    }

    public function getBlockedAssetsAttribute($value)
    {
        return $value/100;
    }

}
