<?php

namespace FSR\Http\Controllers\MasterAdmin;

use FSR;
use FSR\File;
use FSR\Listing;
use FSR\Location;
use FSR\Admin;
use FSR\ListingOffer;
use FSR\Custom\Methods;

use FSR\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

class EditAdminController extends Controller
{
    private $password_input = false;
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
    public function index(Request $request, string $admin_id_string)
    {
        $admin_id = ctype_digit($admin_id_string) ? intval($admin_id_string) : null;
        if ($admin_id === null) {
            return redirect(route('master_admin.admins'));
        } else {
            $admin = Admin::find($admin_id);
            if (!$admin) {
                return redirect(route('master_admin.admins'));
            } else {
                return view('master_admin.edit_admin')->with([
              'admin' => $admin,
          ]);
            }
        }
    }


    /**
     * set information for image upload for editing an existing admin
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function edit_handle_upload(Request $request)
    {
        $input_name = 'admin-image';
        $purpose = 'admin image';
        $for_user_type = 'admin';
        $description = 'An admin image uploaded when editing an existing admin via admins/{{id}}';

        return Methods::handleUpload($request, $input_name, $purpose, $for_user_type, $description);
    }

    /**
     * Get a validator for admin update
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        if ($this->password_input) {
            $validatorArray = [
          'admin-first-name'        => 'required',
          'admin-last-name'         => 'required',
          'admin-image'             => 'image|max:2048',
          'admin-email'             => 'required|string|email|max:255|unique:donors,email|unique:csos,email|unique:volunteers,email|unique:admins,email',
          'admin-password'          => 'required|string|min:6|confirmed',
        ];
        } else {
            $validatorArray = [
          'admin-first-name'        => 'required',
          'admin-last-name'         => 'required',
          'admin-image'             => 'image|max:2048',
          'admin-email'             => 'required|string|email|max:255|unique:donors,email|unique:csos,email|unique:volunteers,email|unique:admins,email',
        ];
        }


        return Validator::make($data, $validatorArray);
    }


    /**
     * Get a validator for update admin (when the email is not changed)
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator_update(array $data)
    {
        if ($this->password_input) {
            $validatorArray = [
          'admin-first-name'        => 'required',
          'admin-last-name'         => 'required',
          'admin-image'             => 'image|max:2048',
          'admin-password'          => 'required|string|min:6|confirmed',
        ];
        } else {
            $validatorArray = [
          'admin-first-name'        => 'required',
          'admin-last-name'         => 'required',
          'admin-image'             => 'image|max:2048',
        ];
        }

        return Validator::make($data, $validatorArray);
    }

    /**
     * Handle "edit admin". - post
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle_post(Request $request)
    {
        $admin_id = $request->all()['admin_id'];
        $admin = Admin::find($admin_id);
        if ($request->all()['admin-password'] == "" && $request->all()['admin-password_confirmation'] == "") {
            $this->password_input = false;
        } else {
            $this->password_input = true;
        }

        if ($this->change_detected($request, $admin)) {
            if ($admin->email != $request->all()['admin-email']) {
                $validation = $this->validator($request->all());
            } else {
                $validation = $this->validator_update($request->all());
            }

            if ($validation->fails()) {
                return back()->withErrors($validation->errors())
                                                 ->withInput();
            }

            $file_id = $this->edit_handle_upload($request);
            $admin = $this->update($admin, $request->all(), $file_id);
            return redirect(route('master_admin.admins'))->with('status', "Измените се успешно зачувани!");
        } else {
            return back();
        }
    }

    /**
     * Indicates if changes are being made to the admin information
     *
     * @param  Request  $request
     * @param  Admin $admin
     * @return bool
     */
    protected function change_detected(Request $request, Admin $admin)
    {
        $data = $request->all();

        if ($request->hasFile('admin-image')) {
            return true;
        }
        if ($admin->first_name != $data['admin-first-name']) {
            return true;
        }
        if ($admin->last_name != $data['admin-last-name']) {
            return true;
        }
        if ($admin->email != $data['admin-email']) {
            return true;
        }
        if ($data['admin-password'] != '') {
            return true;
        }
        if ($data['admin-password_confirmation'] != '') {
            return true;
        }



        return false;
    }

    /**
     * updates the information for the profile
     *
     * @param  Admin $admin
     * @param  array  $data
     * @param  int  $file_id
     * @return FSR\Admin $admin
     */
    protected function update(Admin $admin, array $data, $file_id)
    {
        if ($file_id) {
            $admin->profile_image_id = $file_id;
        }
        $admin->first_name = $data['admin-first-name'];
        $admin->last_name = $data['admin-last-name'];
        $admin->email = $data['admin-email'];
        if ($this->password_input) {
            $admin->password = Hash::make($data['admin-password']);
        }

        $admin->save();

        return $admin;
    }
}
