<?php

namespace App\Http\Middleware;

use App\Http\Models\Assets;
use Closure;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('home/login');
        }
        $id = Auth::id();
        if (!Cache::has('assets'.$id)){
            $assets = Assets::where('member_id',$id)->first();
            Cache::put('assets'.$id,$assets,Carbon::tomorrow());
        }
        //统计在线人数
        $this->countOnline();

        return $next($request);
    }

    private function countOnline()
    {
        $online = Cache::get('online');
        if (!empty($online)){
            $online[Auth::id()] = time();
        }else{
            $online = [Auth::id()=>time()];
        }
        Cache::put('online',$online,Carbon::tomorrow()->setHours(1));
    }
}
