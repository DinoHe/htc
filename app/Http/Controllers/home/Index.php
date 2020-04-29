<?php


namespace App\Http\Controllers\Home;

use App\Http\Controllers\Base;

class Index extends Base
{
    public function index()
    {
        return view('home.index');
    }
}
