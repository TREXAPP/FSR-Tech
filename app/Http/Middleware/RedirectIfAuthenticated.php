<?php

namespace FSR\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard('cso')->check()) {
            return redirect('/cso/home');
        } elseif (Auth::guard('donor')->check()) {
            return redirect('/donor/home');
        } else {
            //return redirect('/');
        }


        return $next($request);
    }
}
