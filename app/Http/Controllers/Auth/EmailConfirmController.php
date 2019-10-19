<?php

namespace FSR\Http\Controllers\Auth;

use FSR\Cso;
use FSR\Donor;
use FSR\Hub;
use FSR\Custom\Methods;

use FSR\Notifications\UserEmailVerificationRequest;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use FSR\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EmailConfirmController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;
    protected $guard = '';

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected function redirectTo()
    {
        switch (Auth::guard($this->guard)->user()->type()) {
        case 'cso':
            return '/cso/home';
          break;
        case 'donor':
            return '/donor/home';
          break;

        default:
        return '/';
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
        //$this->middleware('auth:donor,cso');
    }

    // /**
    //  * Reset the given user's password.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
    //  */
    // public function reset(Request $request)
    // {
    //     $email = $this->credentials($request)['email'];
    //     $this->validate($request, $this->rules(), $this->validationErrorMessages());
    //
    //     // Here we will attempt to reset the user's password. If it is successful we
    //     // will update the password on an actual user model and persist it to the
    //     // database. Otherwise we will parse the error and return the response.
    //     $response = $this->broker($email)->reset(
    //         $this->credentials($request),
    //         function ($user, $password) {
    //             $this->resetPassword($user, $password);
    //         }
    //     );
    //
    //     // If the password was successfully reset, we will redirect the user back to
    //     // the application's home authenticated view. If there is an error we can
    //     // redirect them back to where they came from with their error message.
    //     return $response == Password::PASSWORD_RESET
    //                 ? $this->sendResetResponse($response)
    //                 : $this->sendResetFailedResponse($request, $response);
    // }


    public function showConfirmForm(Request $request, $token = null)
    {
        if (!empty(Auth::user())) {
            switch (Auth::user()->type()) {
                case 'cso':
                    $redirect_link = route('cso.home');
                    break;
                case 'donor':
                    $redirect_link = route('donor.home');
                    break;
                case 'hub':
                    $redirect_link = route('hub.home');
                    break;
                
                default:
                    $redirect_link = route('login');
                    break;
            }
        } else {
            $redirect_link = route('login');
        }
        //zemi go tokenot
        //proveri dali postoi user so toj token
        //proveri dali emailot mu e veke potvrden
        //ako ne - potvrdi. poraka: uspesno potvrden email
        //ako da - poraka: emailot e veke potvrden
        //ako ne postoi - poraka nevaliden link
        //ako nema token - poraka nevaliden link
        if ($token) {
            $csos = Cso::where('email_token', $token)->get();
            if ($csos->count()) {
                $cso = $csos[0];
                if ($cso->email_confirmed) {
                    return redirect($redirect_link)->with('status', 'Вашиот емаил веќе е потврден.');
                } else {
                    $cso->email_confirmed = 1;
                    $cso->save();
                    Methods::log_event('confirm_email', $cso->id, 'cso');
                    return redirect($redirect_link)->with('status', 'Вашиот емаил е успешно потврден!');
                }
            } else {
                $donors = Donor::where('email_token', $token)->get();
                if ($donors->count()) {
                    $donor = $donors[0];
                    if ($donor->email_confirmed) {
                        return redirect($redirect_link)->with('status', 'Вашиот емаил веќе е потврден.');
                    } else {
                        $donor->email_confirmed = 1;
                        $donor->save();
                        Methods::log_event('confirm_email', $donor->id, 'donor');
                        return redirect($redirect_link)->with('status', 'Вашиот емаил е успешно потврден!');
                    }
                } else {
                    $hubs = Hub::where('email_token', $token)->get();
                    if ($hubs->count()) {
                        $hub = $hubs[0];
                        if ($hub->email_confirmed) {
                            return redirect($redirect_link)->with('status', 'Вашиот емаил веќе е потврден.');
                        } else {
                            $hub->email_confirmed = 1;
                            $hub->save();
                            Methods::log_event('confirm_email', $hub->id, 'hub');
                            return redirect($redirect_link)->with('status', 'Вашиот емаил е успешно потврден!');
                        }
                    } else {
                        return redirect($redirect_link)->with('status_error', 'Невалиден линк.');
                    }
                    
                }
            }
        } else {
            return redirect($redirect_link)->with('status_error', 'Невалиден линк.');
        }
    }

    public function resend_link(Request $request)
    {
        Auth::user()->notify(new UserEmailVerificationRequest(Auth::user()));
        return back()->with('status', 'Линкот за активација Ви е препратен на емаил.');
    }
}
