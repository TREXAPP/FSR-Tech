<?php

namespace FSR\Http\Controllers\Hub;

use FSR\File;
use FSR\Listing;
use FSR\Region;
use FSR\Admin;
use FSR\ListingOffer;
use FSR\Custom\Methods;
use FSR\Http\Controllers\Controller;
use FSR\Notifications\UserEditProfile;
use FSR\Notifications\UserToAdminEditProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:hub');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('hub.profile')->with([
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
        return view('hub.edit_profile')->with([
            'user' => Auth::user(),
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
                return redirect(route('hub.edit_profile'))->withErrors($validation->errors())
                                                   ->withInput();
            }

            $file_id = $this->edit_handle_upload($request);
            $user = $this->update_user($request->all(), $file_id);
            $user->notify(new UserEditProfile(Auth::user()));

            $master_admins = Admin::where('master_admin', 1)
                                ->where('status', 'active')->get();
            Notification::send($master_admins, new UserToAdminEditProfile(Auth::user()));

            return redirect(route('hub.profile'))->with('status', "Измените се успешно зачувани!");
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
        $for_user_type = 'hub';
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

        $user->save();

        return $user;
    }

}
