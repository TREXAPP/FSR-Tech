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
        switch ($guard) {
          case 'cso':
            if (Auth::guard($guard)->check()) {
                return redirect()->route('cso.home');
            }
            break;

          case 'donor':
            if (Auth::guard($guard)->check()) {
                return redirect()->route('donor.home');
            }
            break;

          case 'admin':
            if (Auth::guard($guard)->check()) {
                return redirect()->route('admin.home');
            }
            break;

          default:
              //return redirect()->route('home');
            break;
        }
        // if (Auth::guard('cso')->check()) {
        //     return redirect('/cso/home');
        // } elseif (Auth::guard('donor')->check()) {
        //     return redirect('/donor/home');
        // } elseif (Auth::guard('admin')->check()) {
        //     return redirect('/admin/home');
        // } else {
        //     //return redirect('/');
        // }


        return $next($request);
    }
}
