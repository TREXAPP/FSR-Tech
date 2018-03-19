<?php

namespace FSR\Http\Controllers\Admin;

use FSR;
use FSR\File;
use FSR\Listing;
use FSR\Location;
use FSR\ListingOffer;
use FSR\Custom\Methods;

use FSR\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

class EditLocationController extends Controller
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
    public function index(Request $request, string $location_id_string)
    {
        $location_id = ctype_digit($location_id_string) ? intval($location_id_string) : null;
        if ($location_id === null) {
            return redirect(route('admin.locations'));
        } else {
            $location = Location::find($location_id);
            if (!$location) {
                return redirect(route('admin.locations'));
            } else {
                return view('admin.edit_location')->with([
              'location' => $location,
          ]);
            }
        }
    }

    /**
     * Get a validator for a new location insert
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $validatorArray = [
                    'name'    => 'required',
                ];

        return Validator::make($data, $validatorArray);
    }

    /**
     * Handle "edit location". - post
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle_post(Request $request)
    {
        $location_id = $request->all()['location_id'];
        $location = Location::find($location_id);

        if ($this->change_detected($request, $location)) {
            $validation = $this->validator($request->all());


            if ($validation->fails()) {
                return back()->withErrors($validation->errors())
                                                 ->withInput();
            }

            $location = $this->update($location, $request->all());
            return redirect(route('admin.locations'))->with('status', "Измените се успешно зачувани!");
        } else {
            return back();
        }
    }

    /**
     * Indicates if changes are being made to the location information
     *
     * @param  Request  $request
     * @param  Location $location
     * @return bool
     */
    protected function change_detected(Request $request, $location)
    {
        $data = $request->all();

        if ($location->name != $data['name']) {
            return true;
        }
        if ($location->description != $data['description']) {
            return true;
        }

        return false;
    }

    /**
     * updates the information for the profile
     *
     * @param  Location $location
     * @param  array  $data
     * @return FSR\Location $location
     */
    protected function update(Location $location, array $data)
    {
        $location->name = $data['name'];
        $location->description = $data['description'];

        $location->save();

        return $location;
    }
}
