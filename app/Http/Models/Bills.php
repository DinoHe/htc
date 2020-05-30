<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Bills extends Model
{

    protected $fillable = [
        'member_id','tittle','operation'
    ];

    public function member()
    {
        return $this->belongsTo('App\Http\Models\Members','member_id');
     }

   public static function createBill($id,$tittle,$operation)
    {
        self::create([
            'member_id' => $id,
            'tittle' => $tittle,
            'operation' => $operation
        ]);
    }
}
