<?php

namespace FSR\Http\Controllers\Auth;

use FSR\User;
use FSR\Cso;
use FSR\Admin;
use FSR\Donor;
use FSR\Location;
use FSR\Volunteer;
use FSR\Organization;
use FSR\File;
use FSR\Custom\Methods;
use FSR\Notifications\UserToAdminsRegister;


use FSR\Http\Controllers\Controller;
use FSR\Notifications\UserRegistrationSuccess;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
    protected $redirectTo = '/home';

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        $selectedType = old('type');
        if ($selectedType) {
            $organizations = Organization::where('type', '=', $selectedType)
                                          ->where('status', 'active')->get();
        } else {
            $organizations = Organization::where('status', 'active')->get();
        }

        $locations = Location::where('status', 'active')->get();
        return view('auth.register')->with([
          'organizations' => $organizations,
          'locations' => $locations,
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $file_id = $this->register_handle_upload($request);
        event(new Registered($user = $this->create($request->all(), $file_id)));

        if ($user->type() == 'donor' || $user->type() == 'cso') {
            $user->notify(new UserRegistrationSuccess($user));
        }
        $admins = Admin::where('status', 'active')->get();
        Notification::send($admins, new UserToAdminsRegister($user));

        //$request->session()->put('status', Lang::get('login.not_approved'));

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        Methods::log_event('regster', $user->id, $user->type());
        return redirect(route('login'))->with('status', 'Успешно се регистриравте! За да можете да ја користите платформата СитеСити, потребно е да го потврдите Вашиот емаил (со клик на линкот за активација што Ви е пратен предмалку). Исто така, Вашиот профил треба да биде одобрен од администраторот, за што ќе бидете дополнително известени по емаил.');
    }


    /**
     * Retrieve Organizations with ajax to populate the <select> control
     *
     * @param  Illuminate\Http\Request $request
     * @return Collection
     */
    public function getOrganizations(Request $request)
    {
        return $organizations = Organization::where('status', 'active')
                                              ->where('type', $request->input('type'))->get();
    }

    /**
     * Retrieve Address with ajax for the selected organization
     *
     * @param  Illuminate\Http\Request $request
     * @param  $id
     * @return String
     */
    public function getAddress(Request $request, $id = null)
    {
        if ($id) {
            return Organization::find($id)->address;
        } else {
            return "";
        }
    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $validatorArray = [
            'type'                  => 'required',
            'organization'          => 'required',
            'location'              => 'required',
            'email'                 => 'required|string|email|max:255|unique:donors|unique:csos|unique:volunteers',
            'password'              => 'required|string|min:6|confirmed',
            'profile_image'         => 'image|max:2048',
        ];

        return Validator::make($data, $validatorArray);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \FSR\User
     */
    protected function create(array $data, $file_id)
    {
        switch ($data['type']) {
          case 'donor':
          //$redirectTo = '/donor/home';
          $redirectTo = route('login');
          return  Donor::create([
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'organization_id' => $data['organization'],
                'location_id' => $data['location'],
                'profile_image_id' => $file_id,
                'notifications' => '1',
                'email_token' => base64_encode($data['email'])
            ]);

          break;
          case 'cso':
          //$redirectTo = '/cso/home';
          $redirectTo = route('login');
          $cso = Cso::create([
              'email' => $data['email'],
              'password' => bcrypt($data['password']),
              'first_name' => $data['first_name'],
              'last_name' => $data['last_name'],
              'phone' => $data['phone'],
              'address' => $data['address'],
              'organization_id' => $data['organization'],
              'location_id' => $data['location'],
              'profile_image_id' => $file_id,
              'notifications' => '1',
              'email_token' => base64_encode($data['email'])
            ]);
          $volunteer = $this->create_volunteer($data, $file_id, $cso->id);
          return $cso;
          break;

        default:
          # code...
          break;
      }
    }


    /**
     * inserts a new volunteer to the model
     *
     * @param  array  $data
     * @param  int  $file_id
     * @param  int  $cso_id
     * @return FSR\Volunteer $volunteer
     */
    protected function create_volunteer($data, $file_id, $cso_id)
    {
        return Volunteer::create([
                  'first_name' => $data['first_name'],
                  'last_name' => $data['last_name'],
                  'email' => $data['email'],
                  'phone' => $data['phone'],
                  'image_id' => $file_id,
                  'organization_id' => $data['organization'],
                  'added_by_user_id' => $cso_id,
                  'is_user' => '1',
                  'status' => 'pending',
              ]);
    }



    /**
     * set information for image upload for registering new user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function register_handle_upload(Request $request)
    {
        $input_name = 'profile_image';
        $purpose = 'profile image';
        $for_user_type = $request->all()['type'];
        $description = 'Profile image for a ' . $request->all()['type'] . ' uploaded when registering.';

        return Methods::handleUpload($request, $input_name, $purpose, $for_user_type, $description);
    }
}
