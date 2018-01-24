<?php

namespace FSR\Http\Controllers\Admin;

use FSR\Cso;
use FSR\Donor;
use FSR\FoodType;
use FSR\Volunteer;
use FSR\Organization;
use FSR\QuantityType;
use FSR\Custom\Methods;
use FSR\Http\Controllers\Controller;
use FSR\Notifications\Cso\CsoApproved;
use FSR\Notifications\Donor\DonorApproved;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NewQuantityTypeController extends Controller
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
        return view('admin.new_quantity_type');
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

        $quantity_type = $this->create($request->all());

        return back()->with('status', "Количината е додадена успешно!");
    }

    /**
     * Create a new QuantityType instance.
     *
     * @param  array  $data
     * @return \FSR\QuantityType
     */
    protected function create(array $data)
    {
        return  QuantityType::create([
                'name' => $data['name'],
                'description' => $data['description'],
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
        ];

        return Validator::make($data, $validatorArray);
    }
}
