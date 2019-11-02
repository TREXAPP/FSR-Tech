<?php

namespace FSR\Http\Middleware;

use Closure;
use FSR\Custom\Methods;

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
            return redirect(route('cso.home'));
        } elseif (Auth::guard('donor')->check()) {
            return redirect(route('donor.home'));
        } elseif (Auth::guard('hub')->check()) {
            return redirect(route('hub.home'));
        } elseif (Auth::guard('admin')->check()) {
            return redirect(route('admin.home'));
        } else {
            //return redirect('/');
        }


        return $next($request);
    }
}
