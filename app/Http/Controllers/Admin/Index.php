<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base;
use App\Http\Models\Permissions;
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
        //初始化权限
        $this->initPermission();

        return view('admin.index');
    }

    private function initPermission()
    {
        $admin = Auth::guard('admin')->user();
        $p = $admin->roles->permission;
        if ($p == 0){
            $this->request->session()->put('permission',$p);
        }else{
            $pIds = explode(',',$p)?:$p;
            $permissions = Permissions::find($pIds);
            $pa = [];
            foreach ($permissions as $permission) {
                array_push($pa,$permission->url);
            }
            $this->request->session()->put('permission',$pa);
        }

    }
}
