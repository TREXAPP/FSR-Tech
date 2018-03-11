<?php

namespace FSR\Http\Controllers\Admin;

use FSR;
use FSR\Cso;
use FSR\Donor;
use FSR\FoodType;
use FSR\Volunteer;
use FSR\Organization;
use FSR\QuantityType;
use FSR\Custom\Methods;
use FSR\Notifications\AdminToVolunteerNewVolunteer;
use FSR\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NewVolunteerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organizations = Organization::where('type', 'cso')
                                      ->where('status', 'active')->get();
        return view('admin.new_volunteer')->with([
          'organizations' => $organizations,
        ]);
    }

    /**
     * Handle post request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle_post(Request $request)
    {
        $validation = $this->volunteer_validator($request->all());
        //$this->validator($request->all())->validate();
        if ($validation->fails()) {
            return back()->withErrors($validation->errors())
                                                   ->withInput();
        }
        $file_id = $this->new_handle_upload($request);
        $volunteer = $this->create_volunteer($request->all(), $file_id);
        if ($volunteer) {
            $volunteer->notify(new AdminToVolunteerNewVolunteer($volunteer));
            return back()->with('status', "Волонтерот е додаден успешно!");
        } else {
            return back()->with('status', "Грешка при додавање на волонтерот! Контактирајте го администраторот");
        }
    }

    /**
     * set information for image upload for adding new volunteer
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function new_handle_upload(Request $request)
    {
        $input_name = 'volunteer-image';
        $purpose = 'volunteer image';
        $for_user_type = 'admin';
        $description = 'A new volunteer image uploaded when creating a new volunteer by the admin via volunteers/new';

        return Methods::handleUpload($request, $input_name, $purpose, $for_user_type, $description);
    }


    /**
     * Get a validator for a new volunteer insert
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function volunteer_validator(array $data)
    {
        $validatorArray = [
                    'volunteer-organization'  => 'required',
                    'volunteer-first-name'    => 'required',
                    'volunteer-last-name'     => 'required',
                    'volunteer-image'         => 'image|max:2048',
                    'volunteer-email'         => 'required|string|email|max:255|unique:donors,email|unique:csos,email|unique:volunteers,email',
                    'volunteer-phone'         => 'required',
                ];

        return Validator::make($data, $validatorArray);
    }

    /**
     * inserts a new volunteer to the model
     *
     * @param  array  $data
     * @param  int  $file_id
     * @return FSR\Volunteer $volunteer
     */
    protected function create_volunteer($data, $file_id)
    {
        return Volunteer::create([
              'first_name' => $data['volunteer-first-name'],
              'last_name' => $data['volunteer-last-name'],
              'email' => $data['volunteer-email'],
              'phone' => $data['volunteer-phone'],
              'image_id' => $file_id,
              'organization_id' => $data['volunteer-organization'],
              'added_by_user_id' => 0,
          ]);
    }
}
