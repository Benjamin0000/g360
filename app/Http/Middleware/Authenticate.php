<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers;
use Closure;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
     /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $this->authenticate($request, $guards);
        $currentUrl = url()->current();
        if($currentUrl == route('user.package.index') || 
           $currentUrl == route('user.package.select_free') ||
           $currentUrl == route('user.package.show_premium') || 
           $currentUrl == route('user.upgrade')
        )return $next($request);
        if(Auth::user()->pkg_id){
            if(Helpers::ripeForUpgrade()){
                return redirect(route('user.upgrade'));
            }
            return $next($request);
        }
        return redirect(route('user.package.index'))->with('select_a_pkg', 'not empty');
    }
}
