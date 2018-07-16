<?php

namespace FSR\Http\Controllers\Admin;

use FSR;
use FSR\File;
use FSR\Listing;
use FSR\Location;
use FSR\Volunteer;
use FSR\ListingOffer;
use FSR\Custom\Methods;
use FSR\Notifications\CsoToVolunteerRemoved;

use FSR\Http\Controllers\Controller;
use FSR\Notifications\CsoToVolunteerNewVolunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

class EditVolunteerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:master_admin,admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, string $volunteer_id_string)
    {
        $volunteer_id = ctype_digit($volunteer_id_string) ? intval($volunteer_id_string) : null;
        if ($volunteer_id === null) {
            return redirect(route('admin.volunteers'));
        } else {
            $volunteer = Volunteer::find($volunteer_id);
            if (!$volunteer) {
                return redirect(route('admin.volunteers'));
            } else {
                return view('admin.edit_volunteer')->with([
              'volunteer' => $volunteer,
          ]);
            }
        }
    }


    /**
     * set information for image upload for editing an existing volunteer
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function edit_handle_upload(Request $request)
    {
        $input_name = 'volunteer-image';
        $purpose = 'volunteer image';
        $for_user_type = 'cso';
        $description = 'A volunteer image uploaded when editing an existing volunteer via volunteers/{{id}}';

        return Methods::handleUpload($request, $input_name, $purpose, $for_user_type, $description);
    }

    /**
     * Get a validator for a new volunteer insert
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $validatorArray = [
                    'volunteer-first-name'    => 'required',
                    'volunteer-last-name'     => 'required',
                    'volunteer-image'         => 'image|max:2048',
                    'volunteer-email'         => 'required|string|email|max:255|unique:donors,email|unique:csos,email|unique:volunteers,email|unique:admins,email',
                    'volunteer-phone'         => 'required',
                ];

        return Validator::make($data, $validatorArray);
    }


    /**
     * Get a validator for update volunteer (when the email is not changed)
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator_update(array $data)
    {
        $validatorArray = [
                    'volunteer-first-name'    => 'required',
                    'volunteer-last-name'     => 'required',
                    'volunteer-image'         => 'image|max:2048',
                    'volunteer-phone'         => 'required',
                ];

        return Validator::make($data, $validatorArray);
    }

    /**
     * Handle "edit volunteer". - post
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle_post(Request $request)
    {
        $volunteer_id = $request->all()['volunteer_id'];
        $volunteer = Volunteer::find($volunteer_id);

        if ($this->change_detected($request, $volunteer)) {
            if ($volunteer->email != $request->all()['volunteer-email']) {
                $validation = $this->validator($request->all());
            } else {
                $validation = $this->validator_update($request->all());
            }


            if ($validation->fails()) {
                return back()->withErrors($validation->errors())
                                                 ->withInput();
            }

            $file_id = $this->edit_handle_upload($request);
            $volunteer = $this->update($volunteer, $request->all(), $file_id);
            return redirect(route('admin.volunteers'))->with('status', "Измените се успешно зачувани!");
        } else {
            return back();
        }
    }

    /**
     * Indicates if changes are being made to the volunteer information
     *
     * @param  Request  $request
     * @param  Volunteer $volunteer
     * @return bool
     */
    protected function change_detected(Request $request, Volunteer $volunteer)
    {
        $data = $request->all();

        if ($request->hasFile('volunteer-image')) {
            return true;
        }
        if ($volunteer->first_name != $data['volunteer-first-name']) {
            return true;
        }
        if ($volunteer->last_name != $data['volunteer-last-name']) {
            return true;
        }
        if ($volunteer->email != $data['volunteer-email']) {
            return true;
        }

        if ($volunteer->phone != $data['volunteer-phone']) {
            return true;
        }


        return false;
    }

    /**
     * updates the information for the profile
     *
     * @param  Volunteer $volunteer
     * @param  array  $data
     * @param  int  $file_id
     * @return FSR\Volunteer $volunteer
     */
    protected function update(Volunteer $volunteer, array $data, $file_id)
    {
        if ($file_id) {
            $volunteer->image_id = $file_id;
        }
        $volunteer->first_name = $data['volunteer-first-name'];
        $volunteer->last_name = $data['volunteer-last-name'];
        $volunteer->email = $data['volunteer-email'];
        $volunteer->phone = $data['volunteer-phone'];

        $volunteer->save();

        return $volunteer;
    }
}
