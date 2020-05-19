<?php

namespace App\Http\Middleware;

use App\Http\Models\SystemLogs;
use Closure;
use Illuminate\Support\Facades\Auth;

class AdminRedirectIfAuthenticated
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
        if (!Auth::guard('admin')->check()) {
            return redirect('admin/login');
        }
        if ($request->isMethod('post')){
            $path = $request->path();
            $content = $request->input();
            $content = json_encode($content);
            $ip = $request->ip();
            $event = '';
            if (strpos($path,'Add')){
                $event = '新增';
            }else if (strpos($path,'Edit')){
                $event = '更新';
            }else if (strpos($path,'Del')){
                $event = '删除';
                $content = $request->input('content');
            }
            if ($event != ''){
                SystemLogs::createLog($event,Auth::guard('admin')->user()->account,$ip,$content);
            }
        }

        return $next($request);
    }
}
