<?php


namespace App\Http\Controllers\Home;

use App\Http\Controllers\Base;
use App\Http\Models\Miners;

class Index extends Base
{
    public function index()
    {
        $miners = Miners::all();
        return view('home.index')->with('miners',$miners);
    }

    public function qiandao()
    {
        return $this->dataReturn(['status'=>0,'message'=>'签到成功']);
    }
}
