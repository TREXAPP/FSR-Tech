<?php

namespace FSR\Http\Controllers\Admin;

use FSR;
use FSR\Donor;
use FSR\File;
use FSR\Listing;
use FSR\Location;
use FSR\ListingOffer;
use FSR\Organization;
use FSR\Custom\Methods;
use FSR\Notifications\DonorToVolunteerRemoved;

use FSR\Http\Controllers\Controller;
use FSR\Notifications\DonorToVolunteerNewVolunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

class EditDonorUserController extends Controller
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
    public function index(Request $request, string $donor_id_string)
    {
        $donor_id = ctype_digit($donor_id_string) ? intval($donor_id_string) : null;
        $organizations = Organization::where('status', 'active')
                                      ->where('type', 'donor')->get();
        $locations = Location::where('status', 'active')->get();

        if ($donor_id === null) {
            return redirect(route('admin.donor_users'));
        } else {
            $donor = Donor::find($donor_id);
            if (!$donor) {
                return redirect(route('admin.donor_users'));
            } else {
                return view('admin.edit_donor_user')->with([
              'donor' => $donor,
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
        $input_name = 'donor-image';
        $purpose = 'donor image';
        $for_user_type = 'donor';
        $description = 'A donor image uploaded when editing an existing donor via users/donor/{{id}}';

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
                'donor-organization'          => 'required',
                'donor-location'              => 'required',
                'donor-image'         => 'image|max:2048',
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
        $donor_id = $request->all()['donor_id'];
        $donor = Donor::find($donor_id);

        if ($this->change_detected($request, $donor)) {
            $validation = $this->validator($request->all());

            if ($validation->fails()) {
                return back()->withErrors($validation->errors())
                                                 ->withInput();
            }

            $file_id = $this->edit_handle_upload($request);
            $donor = $this->update($donor, $request->all(), $file_id);
            return redirect(route('admin.donor_users'))->with('status', "Измените се успешно зачувани!");
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
    protected function change_detected(Request $request, Donor $donor)
    {
        $data = $request->all();

        if ($request->hasFile('donor-image')) {
            return true;
        }
        if ($donor->first_name != $data['donor-first-name']) {
            return true;
        }
        if ($donor->last_name != $data['donor-last-name']) {
            return true;
        }

        if ($donor->phone != $data['donor-phone']) {
            return true;
        }

        if ($donor->address != $data['donor-address']) {
            return true;
        }

        if ($donor->organization_id != $data['donor-organization']) {
            return true;
        }

        if ($donor->location_id != $data['donor-location']) {
            return true;
        }


        return false;
    }

    /**
     * updates the information for the profile
     *
     * @param  Donor $volunteer
     * @param  array  $data
     * @param  int  $file_id
     * @return FSR\Donor $donor
     */
    protected function update(Donor $donor, array $data, $file_id)
    {
        if ($file_id) {
            $donor->profile_image_id = $file_id;
        }
        $donor->first_name = $data['donor-first-name'];
        $donor->last_name = $data['donor-last-name'];
        $donor->phone = $data['donor-phone'];
        $donor->address = $data['donor-address'];
        $donor->organization_id = $data['donor-organization'];
        $donor->location_id = $data['donor-location'];

        $donor->save();

        return $donor;
    }
}
