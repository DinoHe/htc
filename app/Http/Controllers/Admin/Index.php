<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base;
use App\Http\Models\SystemLogs;
use Illuminate\Support\Facades\Auth;

class Index extends Base
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        $account = $admin->account;
        $role = $admin->roles->name;
        $log = SystemLogs::where('account',$admin->account)->orderBy('id','desc')->limit(1,1)->first();
        $lastIp = $log->ip;
        $lastTime = $log->created_at;
        $this->request->session()->put('admin',['account'=>$account,'role'=>$role,'lastIp'=>$lastIp,'lastTime'=>$lastTime]);
        return view('admin.index');
    }
}
