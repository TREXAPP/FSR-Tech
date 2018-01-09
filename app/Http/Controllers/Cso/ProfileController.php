<?php

namespace FSR\Http\Controllers\Cso;

use FSR\File;
use FSR\Listing;
use FSR\Location;
use FSR\Volunteer;
use FSR\ListingOffer;
use FSR\Custom\Methods;
use FSR\Http\Controllers\Controller;
use FSR\Notifications\Cso\EditProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:cso');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('cso.profile')->with([
          'user' => Auth::user(),
        ]);
    }

    /**
     * Show the application dashboard.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function edit_profile(Request $request)
    {
        $locations = Location::all();
        return view('cso.edit_profile')->with([
          'user' => Auth::user(),
        'locations' => $locations,
        ]);
    }
    /**
     * Show the application dashboard.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function handle_post(Request $request)
    {
        if ($this->change_detected($request)) {
            $validation = $this->validator($request->all());

            if ($validation->fails()) {
                return redirect(route('cso.edit_profile'))->withErrors($validation->errors())
                                                   ->withInput();
            }

            $file_id = $this->edit_handle_upload($request);
            $volunteer = $this->update_volunteer($request->all(), $file_id);
            $user = $this->update_user($request->all(), $file_id);

            $user->notify(new EditProfile());

            return redirect(route('cso.profile'))->with('status', "Измените се успешно зачувани!");
        } else {
            return back();
        }
    }


    /**
     * Get a validator for an incoming profile edit request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $validatorArray = [
                'profile-first-name'    => 'required',
                'profile-last-name'     => 'required',
                'profile-image'         => 'image|max:2048',
                'profile-address'       => 'required',
                'profile-phone'         => 'required',
                'profile-location'      => 'required',
            ];

        return Validator::make($data, $validatorArray);
    }

    /**
     * Indicates if changes are being made
     *
     * @param  Request  $request
     * @return bool
     */
    protected function change_detected(Request $request)
    {
        $data = $request->all();

        if ($request->hasFile('profile-image')) {
            return true;
        }
        if (Auth::user()->first_name != $data['profile-first-name']) {
            return true;
        }
        if (Auth::user()->last_name != $data['profile-last-name']) {
            return true;
        }
        if (Auth::user()->address != $data['profile-address']) {
            return true;
        }
        if (Auth::user()->phone != $data['profile-phone']) {
            return true;
        }
        if (Auth::user()->location_id != $data['profile-location']) {
            return true;
        }

        return false;
    }

    /**
     * set information for image upload for editing a profile info
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function edit_handle_upload(Request $request)
    {
        $input_name = 'profile-image';
        $purpose = 'profile image';
        $for_user_type = 'cso';
        $description = 'A profile image uploaded when editing a profile.';

        return Methods::handleUpload($request, $input_name, $purpose, $for_user_type, $description);
    }

    /**
     * updates the information for the profile
     *
     * @param  array  $data
     * @param  int  $file_id
     * @return FSR\Cso $user
     */
    protected function update_user($data, $file_id)
    {
        $user = Auth::user();
        if ($file_id) {
            $user->profile_image_id = $file_id;
        }
        $user->first_name = $data['profile-first-name'];
        $user->last_name = $data['profile-last-name'];
        $user->address = $data['profile-address'];
        $user->phone = $data['profile-phone'];
        $user->location_id = $data['profile-location'];

        $user->save();

        return $user;
    }

    /**
     * updates the information for the volunteer
     *
     * @param  array  $data
     * @param  int  $file_id
     * @return FSR\Volunteer $volunteer
     */
    protected function update_volunteer($data, $file_id)
    {
        $volunteer = Volunteer::where('is_user', '1')
                              ->where('added_by_user_id', Auth::user()->id)->first();

        if ($volunteer) {
            if ($file_id) {
                $volunteer->image_id = $file_id;
            }
            $volunteer->first_name = $data['profile-first-name'];
            $volunteer->last_name = $data['profile-last-name'];
            $volunteer->phone = $data['profile-phone'];

            $volunteer->save();
        }
        return $volunteer;
    }
}
