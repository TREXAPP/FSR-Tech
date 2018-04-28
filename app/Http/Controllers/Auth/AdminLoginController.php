<?php

namespace FSR\Http\Controllers\Auth;

use FSR\Donor;
use FSR\Cso;
use FSR\Custom\Methods;
use Illuminate\Http\Request;
use FSR\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class AdminLoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        if (session('status')) {
            $status = session('status');
            Session::forget('status');
        } else {
            $status = '';
        }
        return view('auth.admin_login')->with('status', $status);
    }



    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected function redirectTo()
    {
        switch (Auth::user()->type()) {
        case 'admin':
            return '/admin/home';
          break;

        default:
        return '/login';
          break;
      }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }
        if ($request->all()['email'] == config('app.master_admin')) {
            if ($this->attemptLogin($request, 'master_admin')) {
                Auth::setUser((Auth::guard('master_admin')->user()));
                return $this->sendLoginResponse($request);
            }
        } else {
            if ($this->attemptLogin($request, 'admin')) {
                Auth::setUser((Auth::guard('admin')->user()));
                return $this->sendLoginResponse($request);
            }
        }


        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request, String $guard)
    {
        $credentials = $this->credentials($request);
        return  Auth::guard($guard)->attempt(
            $credentials,
            $request->filled('remember')
        );
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $guard = "";
        if (Auth::guard('master_admin')->user()) {
            $guard = 'master_admin';
        } else {
            if (Auth::guard('admin')->user()) {
                $guard = 'admin';
            }
        }

        if ($guard) {
            $this->guard($guard)->logout();
            $request->session()->invalidate();
            // Get remember_me cookie name
            $str_to_replace = explode('_', Auth::getRecallerName())[1];

            $rememberMeCookie = str_replace($str_to_replace, $guard, Auth::getRecallerName());

            // Tell Laravel to forget this cookie
            $cookie = Cookie::forget($rememberMeCookie);
        }

        return redirect(route('admin.login'))->withCookie($cookie);
    }
}
