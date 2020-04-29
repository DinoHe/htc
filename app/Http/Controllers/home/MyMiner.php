<?php
namespace App\Http\Controllers\Home;

use App\Http\Controllers\Base;

class MyMiner extends Base
{
    public function running()
    {
        return view('home.myminer.running');
    }

    public function finished()
    {
        return view('home.myminer.finished');
    }
}