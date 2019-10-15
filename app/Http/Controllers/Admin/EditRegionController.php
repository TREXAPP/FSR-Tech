<?php

namespace FSR\Http\Controllers\Admin;

use FSR;
use FSR\File;
use FSR\Listing;
use FSR\Location;
use FSR\Region;
use FSR\ListingOffer;
use FSR\Custom\Methods;

use FSR\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

class EditRegionController extends Controller
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
    public function index(Request $request, string $region_id_string)
    {
        $region_id = ctype_digit($region_id_string) ? intval($region_id_string) : null;
        if ($region_id === null) {
            return redirect(route('admin.regions'));
        } else {
            $region = Region::find($region_id);
            if (!$region) {
                return redirect(route('admin.regions'));
            } else {
                return view('admin.edit_region')->with([
              'region' => $region,
          ]);
            }
        }
    }

    /**
     * Get a validator for a new region insert
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
     * Handle "edit region". - post
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle_post(Request $request)
    {
        $region_id = $request->all()['region_id'];
        $region = Region::find($region_id);

        if ($this->change_detected($request, $region)) {
            $validation = $this->validator($request->all());


            if ($validation->fails()) {
                return back()->withErrors($validation->errors())
                                                 ->withInput();
            }

            $region = $this->update($region, $request->all());
            return redirect(route('admin.regions'))->with('status', "Измените се успешно зачувани!");
        } else {
            return back();
        }
    }

    /**
     * Indicates if changes are being made to the region information
     *
     * @param  Request  $request
     * @param  Region $region
     * @return bool
     */
    protected function change_detected(Request $request, $region)
    {
        $data = $request->all();

        if ($region->name != $data['name']) {
            return true;
        }
        if ($region->description != $data['description']) {
            return true;
        }

        return false;
    }

    /**
     * updates the information for the profile
     *
     * @param  Region $region
     * @param  array  $data
     * @return FSR\Region $region
     */
    protected function update(Region $region, array $data)
    {
        $region->name = $data['name'];
        $region->description = $data['description'];

        $region->save();

        return $region;
    }
}
