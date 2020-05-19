<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{

    protected $fillable = [
        'name','permission'
    ];

    public function admins()
    {
        return $this->hasMany('App\Http\Models\Admins','role_id');
    }
}
