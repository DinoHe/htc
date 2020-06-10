<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base;
use App\Http\Models\Coins;
use App\Http\Models\Members;
use App\Http\Models\MyMiners;
use App\Http\Models\Orders;
use App\Http\Models\Permissions;
use App\Http\Models\SystemLogs;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

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
        //统计当前在线人数
        $online = $this->countOnline();
        //获取今日价格
        $price = Coins::orderBy('id','desc')->first()->price;

        return view('admin.index',['online'=>$online,'price'=>$price]);
    }

    /**
     * 统计在线矿机数量 当天注册的会员人数 成交额
     * @return false|string
     */
    public function count()
    {
        $countMiners = MyMiners::where('run_status',MyMiners::RUNNING)->count();
        $countRegister = Members::where('created_at','>=',date('Y-m-d'))->count();
        $countMoney = $this->countTradeMoney();
        return $this->dataReturn(['status'=>0,'countMiners'=>$countMiners,'countRegister'=>$countRegister,
            'countMoney'=>$countMoney]);
    }

    private function countTradeMoney():string
    {
        $countTradeMoney = [];
        for ($i = 6;$i >= 0;$i--){
            $dateStart = Carbon::now()->subDays($i)->toDateString();
            $dateEnd = Carbon::now()->subDays($i-1)->toDateString();
            $totalMoney = Orders::WhereBetween('created_at',[$dateStart,$dateEnd])->where('trade_status',Orders::TRADE_FINISHED)
                ->sum('trade_total_money');
            array_push($countTradeMoney,$totalMoney/100);
        }

        return implode(',',$countTradeMoney);
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

    private function countOnline()
    {
        $online = Cache::get('online');
        $n = 0;
        if (!empty($online)){
            foreach ($online as $k => $on) {
                if (time() - $on < 10*60){
                    $n++;
                }
            }
        }
        return $n;
    }
}
