<?php


namespace App\Http\Controllers\Home;

use App\Http\Controllers\Base;

class Index extends Base
{
    public function index()
    {
        return view('home.index');
    }

    public function qiandao()
    {
        return $this->dataReturn(['status'=>0,'message'=>'签到成功']);
    }
}
