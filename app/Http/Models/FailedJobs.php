<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class FailedJobs extends Model
{

    protected $fillable = [
        'connection','queue','payload','exception'
    ];
}
