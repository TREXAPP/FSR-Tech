<?php

namespace FSR\Http\Controllers\Admin;

use FSR;
use FSR\Hub;
use FSR\File;
use FSR\Listing;
use FSR\Region;
use FSR\ListingOffer;
use FSR\Organization;
use FSR\Custom\Methods;
use FSR\Notifications\HubToVolunteerRemoved;

use FSR\Http\Controllers\Controller;
use FSR\Notifications\HubToVolunteerNewVolunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

class EditHubUserController extends Controller
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
    public function index(Request $request, string $hub_id_string)
    {
        $hub_id = ctype_digit($hub_id_string) ? intval($hub_id_string) : null;
        $organizations = Organization::where('status', 'active')
                                      ->where('type', 'hub')->get();
        $regions = Region::where('status', 'active')->get();

        if ($hub_id === null) {
            return redirect(route('admin.hub_users'));
        } else {
            $hub = Hub::find($hub_id);
            if (!$hub) {
                return redirect(route('admin.hub_users'));
            } else {
                return view('admin.edit_hub_user')->with([
              'hub' => $hub,
              'organizations' => $organizations,
              'regions' => $regions,
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
        $input_name = 'hub-image';
        $purpose = 'hub image';
        $for_user_type = 'hub';
        $description = 'A hub image uploaded when editing an existing hub via users/hub/{{id}}';

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
                'hub-organization'          => 'required',
                'hub-region'              => 'required',
                'hub-image'         => 'image|max:2048',
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
        $hub_id = $request->all()['hub_id'];
        $hub = Hub::find($hub_id);

        if ($this->change_detected($request, $hub)) {
            $validation = $this->validator($request->all());

            if ($validation->fails()) {
                return back()->withErrors($validation->errors())
                                                 ->withInput();
            }

            $file_id = $this->edit_handle_upload($request);
            $hub = $this->update($hub, $request->all(), $file_id);
            return redirect(route('admin.hub_users'))->with('status', "Измените се успешно зачувани!");
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
    protected function change_detected(Request $request, Hub $hub)
    {
        $data = $request->all();

        if ($request->hasFile('hub-image')) {
            return true;
        }
        if ($hub->first_name != $data['hub-first-name']) {
            return true;
        }
        if ($hub->last_name != $data['hub-last-name']) {
            return true;
        }

        if ($hub->phone != $data['hub-phone']) {
            return true;
        }

        if ($hub->address != $data['hub-address']) {
            return true;
        }

        if ($hub->organization_id != $data['hub-organization']) {
            return true;
        }

        if ($hub->region_id != $data['hub-region']) {
            return true;
        }


        return false;
    }

    /**
     * updates the information for the profile
     *
     * @param  Hub $volunteer
     * @param  array  $data
     * @param  int  $file_id
     * @return FSR\Hub $hub
     */
    protected function update(Hub $hub, array $data, $file_id)
    {
        if ($file_id) {
            $hub->profile_image_id = $file_id;
        }
        $hub->first_name = $data['hub-first-name'];
        $hub->last_name = $data['hub-last-name'];
        $hub->phone = $data['hub-phone'];
        $hub->address = $data['hub-address'];
        $hub->organization_id = $data['hub-organization'];
        $hub->region_id = $data['hub-region'];

        $hub->save();

        return $hub;
    }
}
