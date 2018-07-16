<?php

namespace FSR\Http\Controllers\MasterAdmin;

use FSR;
use FSR\Cso;
use FSR\Admin;
use FSR\Donor;
use FSR\FoodType;
use FSR\Volunteer;
use FSR\Organization;
use FSR\QuantityType;
use FSR\Custom\Methods;
use FSR\Notifications\MasterAdminToAdminNewAdmin;

use FSR\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class NewAdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:master_admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('master_admin.new_admin');
    }

    /**
     * Handle post request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle_post(Request $request)
    {
        $validation = $this->admin_validator($request->all());
        if ($validation->fails()) {
            return back()->withErrors($validation->errors())
                                                   ->withInput();
        }
        $file_id = $this->new_handle_upload($request);
        $admin = $this->create_admin($request->all(), $file_id);
        if ($admin) {
            $admin->notify(new MasterAdminToAdminNewAdmin($admin));
            return back()->with('status', "Администраторот е додаден успешно!");
        } else {
            return back()->with('status', "Грешка при додавање на администраторот!");
        }
    }

    /**
     * set information for image upload for adding new admin
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function new_handle_upload(Request $request)
    {
        $input_name = 'admin-image';
        $purpose = 'admin image';
        $for_user_type = 'admin';
        $description = 'A new admin image uploaded when creating a new admin by the master admin';

        return Methods::handleUpload($request, $input_name, $purpose, $for_user_type, $description);
    }


    /**
     * Get a validator for a new admin insert
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function admin_validator(array $data)
    {
        $validatorArray = [
                    'admin-first-name'        => 'required',
                    'admin-last-name'         => 'required',
                    'admin-image'             => 'image|max:2048',
                    'admin-email'             => 'required|string|email|max:255|unique:donors,email|unique:csos,email|unique:volunteers,email|unique:admins,email',
                    'admin-password'          => 'required|string|min:6|confirmed',
                ];

        return Validator::make($data, $validatorArray);
    }

    /**
     * inserts a new admin to the model
     *
     * @param  array  $data
     * @param  int  $file_id
     * @return FSR\Admin $admin
     */
    protected function create_admin($data, $file_id)
    {
        return Admin::create([
              'first_name' => $data['admin-first-name'],
              'last_name' => $data['admin-last-name'],
              'password' => Hash::make($data['admin-password']),
              'email' => $data['admin-email'],
              'profile_image_id' => $file_id,
              'master_admin' => 0,
          ]);
    }
}
