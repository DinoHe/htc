<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSettings extends Model
{

    protected $fillable = [
        'tittle','value','describes','input_type'
    ];

    public static function getSysSettingValue($key)
    {
        return self::where('tittle',$key)->first()->value;
    }
}
