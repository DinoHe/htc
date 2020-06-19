<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Coins extends Model
{

    protected $fillable = [
        'price'
    ];

    public function setPriceAttribute($price)
    {
        $this->attributes['price'] = (int)($price * 100);
    }

    public function getPriceAttribute($price)
    {
        return $price / 100;
    }
}
