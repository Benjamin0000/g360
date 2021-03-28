<?php

namespace App\Http\Middleware\Admin;
use Illuminate\Support\Facades\Auth;
use Closure;

class RedirectIfAdmin
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
        if(Auth::guard('admin')->check()){
            return redirect()->route('admin.dashboard.index');
        }
        return $next($request);
    }
}
