<?php

namespace FSR\Http\Controllers\Admin;

use FSR;
use FSR\File;
use FSR\Listing;
use FSR\DonorType;
use FSR\donor_type;
use FSR\ListingOffer;
use FSR\Custom\Methods;

use FSR\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

class EditDonorTypeController extends Controller
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
    public function index(Request $request, string $donor_type_id_string)
    {
        $donor_type_id = ctype_digit($donor_type_id_string) ? intval($donor_type_id_string) : null;
        if ($donor_type_id === null) {
            return redirect(route('admin.donor_types'));
        } else {
            $donor_type = DonorType::find($donor_type_id);
            if (!$donor_type) {
                return redirect(route('admin.donor_types'));
            } else {
                return view('admin.edit_donor_type')->with([
              'donor_type' => $donor_type,
          ]);
            }
        }
    }

    /**
     * Get a validator for a new donor_type insert
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
     * Handle "edit donor_type". - post
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle_post(Request $request)
    {
        $donor_type_id = $request->all()['donor_type_id'];
        $donor_type = DonorType::find($donor_type_id);

        if ($this->change_detected($request, $donor_type)) {
            $validation = $this->validator($request->all());


            if ($validation->fails()) {
                return back()->withErrors($validation->errors())
                                                 ->withInput();
            }

            $donor_type = $this->update($donor_type, $request->all());
            return redirect(route('admin.donor_types'))->with('status', "Измените се успешно зачувани!");
        } else {
            return back();
        }
    }

    /**
     * Indicates if changes are being made to the donor_type information
     *
     * @param  Request  $request
     * @param  DonorType $donor_type
     * @return bool
     */
    protected function change_detected(Request $request, $donor_type)
    {
        $data = $request->all();

        if ($donor_type->name != $data['name']) {
            return true;
        }
        if ($donor_type->description != $data['description']) {
            return true;
        }

        return false;
    }

    /**
     * updates the information for the profile
     *
     * @param  DonorType $donor_type
     * @param  array  $data
     * @return FSR\DonorType $donor_type
     */
    protected function update(DonorType $donor_type, array $data)
    {
        $donor_type->name = $data['name'];
        $donor_type->description = $data['description'];

        $donor_type->save();

        return $donor_type;
    }
}
