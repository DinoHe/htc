<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class SystemLogs extends Model
{

    protected $fillable = [
        'event','account','ip','content'
    ];

    public static function createLog($event,$account,$ip,$content)
    {
        self::create([
            'event' => $event,
            'account' => $account,
            'ip' => $ip,
            'content' => $content
        ]);
    }
}
