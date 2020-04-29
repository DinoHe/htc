<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class PhoneTmps extends Model
{

    const CHECKED = 0;
    const CHECK_NO = 1;

    protected $fillable = [
        'phone','code'
    ];
}
