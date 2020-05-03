<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Miners extends Model
{

    protected $fillable = [
        'tittle','coin_number','runtime','nph','total_dig','hashrate','amount'
    ];
}
