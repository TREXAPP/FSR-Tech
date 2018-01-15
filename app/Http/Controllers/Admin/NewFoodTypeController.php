<?php

namespace FSR\Http\Controllers\Admin;

use FSR\Cso;
use FSR\Donor;
use FSR\FoodType;
use FSR\Volunteer;
use FSR\Organization;
use FSR\Custom\Methods;
use FSR\Http\Controllers\Controller;
use FSR\Notifications\Cso\CsoApproved;
use FSR\Notifications\Donor\DonorApproved;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NewFoodTypeController extends Controller
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
        return view('admin.new_food_type');
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

        $file_id = $this->new_food_type_handle_upload($request);
        $food_type = $this->create($request->all(), $file_id);

        return back()->with('status', "Категоријата е додадена успешно!");
    }

    /**
     * Create a new organization instance.
     *
     * @param  array  $data
     * @return \FSR\Organization
     */
    protected function create(array $data, $file_id)
    {
        return  FoodType::create([
                'name' => $data['name'],
                'description' => $data['description'],
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
        $validatorArray = [
            'name'                    => 'required',
            'image'                   => 'image|max:2048',
        ];

        return Validator::make($data, $validatorArray);
    }

    /**
     * set information for image upload for adding new organization
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function new_food_type_handle_upload(Request $request)
    {
        $input_name = 'image';
        $purpose = 'food type image';
        $for_user_type = Auth::user()->type();
        $description = 'An uploaded image for a new food type.';

        return Methods::handleUpload($request, $input_name, $purpose, $for_user_type, $description);
    }
}
