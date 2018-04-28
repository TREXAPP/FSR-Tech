<?php

namespace FSR\Http\Controllers\Admin;

use FSR;
use FSR\Cso;
use FSR\File;
use FSR\Listing;
use FSR\Location;
use FSR\ListingOffer;
use FSR\Organization;
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

class EditCsoUserController extends Controller
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
    public function index(Request $request, string $cso_id_string)
    {
        $cso_id = ctype_digit($cso_id_string) ? intval($cso_id_string) : null;
        $organizations = Organization::where('status', 'active')->get();
        $locations = Location::where('status', 'active')->get();

        if ($cso_id === null) {
            return redirect(route('admin.cso_users'));
        } else {
            $cso = Cso::find($cso_id);
            if (!$cso) {
                return redirect(route('admin.cso_users'));
            } else {
                return view('admin.edit_cso_user')->with([
              'cso' => $cso,
              'organizations' => $organizations,
              'locations' => $locations,
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
        $input_name = 'cso-image';
        $purpose = 'cso image';
        $for_user_type = 'cso';
        $description = 'A cso image uploaded when editing an existing cso via users/cso/{{id}}';

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
                'cso-organization'          => 'required',
                'cso-location'              => 'required',
                'cso-image'         => 'image|max:2048',
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
        $cso_id = $request->all()['cso_id'];
        $cso = Cso::find($cso_id);

        if ($this->change_detected($request, $cso)) {
            $validation = $this->validator($request->all());

            if ($validation->fails()) {
                return back()->withErrors($validation->errors())
                                                 ->withInput();
            }

            $file_id = $this->edit_handle_upload($request);
            $cso = $this->update($cso, $request->all(), $file_id);
            return redirect(route('admin.cso_users'))->with('status', "Измените се успешно зачувани!");
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
    protected function change_detected(Request $request, Cso $cso)
    {
        $data = $request->all();

        if ($request->hasFile('cso-image')) {
            return true;
        }
        if ($cso->first_name != $data['cso-first-name']) {
            return true;
        }
        if ($cso->last_name != $data['cso-last-name']) {
            return true;
        }

        if ($cso->phone != $data['cso-phone']) {
            return true;
        }

        if ($cso->address != $data['cso-address']) {
            return true;
        }

        if ($cso->organization_id != $data['cso-organization']) {
            return true;
        }

        if ($cso->location_id != $data['cso-location']) {
            return true;
        }


        return false;
    }

    /**
     * updates the information for the profile
     *
     * @param  Cso $volunteer
     * @param  array  $data
     * @param  int  $file_id
     * @return FSR\Cso $cso
     */
    protected function update(Cso $cso, array $data, $file_id)
    {
        if ($file_id) {
            $cso->profile_image_id = $file_id;
        }
        $cso->first_name = $data['cso-first-name'];
        $cso->last_name = $data['cso-last-name'];
        $cso->phone = $data['cso-phone'];
        $cso->address = $data['cso-address'];
        $cso->organization_id = $data['cso-organization'];
        $cso->location_id = $data['cso-location'];

        $cso->save();

        return $cso;
    }
}
