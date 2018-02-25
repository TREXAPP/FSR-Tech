<?php

namespace FSR\Http\Controllers\Admin;

use FSR\Cso;
use FSR\Donor;
use FSR\Volunteer;
use FSR\Organization;
use FSR\Custom\Methods;
use FSR\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NewOrganizationController extends Controller
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
        return view('admin.new_organization');
    }

    /**
     * Handle post request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle_post(Request $request)
    {
        $validation = $this->validator($request->all());
        //$this->validator($request->all())->validate();
        if ($validation->fails()) {
            return back()->withErrors($validation->errors())
                                                   ->withInput();
        }

        $file_id = $this->new_organization_handle_upload($request);
        $organization = $this->create($request->all(), $file_id);

        return back()->with('status', "Организацијата е додадена успешно!");
    }

    /**
     * Create a new organization instance.
     *
     * @param  array  $data
     * @param  int  $file_id
     * @return \FSR\Organization
     */
    protected function create(array $data, $file_id)
    {
        return  Organization::create([
                'name' => $data['name'],
                'address' => $data['address'],
                'description' => $data['description'],
                'type' => $data['type'],
                'working_hours_from' => $data['working_hours_from'],
                'working_hours_to' => $data['working_hours_to'],
                'image_id' => $file_id,
            ]);
    }

    /**
     * Get a validator for a new organization.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        if ($data['type'] == 'donor') {
            $validatorArray = [
            'name'                    => 'required',
            'working_hours_from'      => 'required',
            'working_hours_to'        => 'required',
            'image'                   => 'image|max:2048',
        ];
        } else {
            $validatorArray = [
            'name'                    => 'required',
            'image'                   => 'image|max:2048',
        ];
        }


        return Validator::make($data, $validatorArray);
    }

    /**
     * set information for image upload for adding new organization
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function new_organization_handle_upload(Request $request)
    {
        $input_name = 'image';
        $purpose = 'organization image';
        $for_user_type = $request->all()['type'];
        $description = 'An uploaded image for a new organization.';

        return Methods::handleUpload($request, $input_name, $purpose, $for_user_type, $description);
    }
}
