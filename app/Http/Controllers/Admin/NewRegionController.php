<?php

namespace FSR\Http\Controllers\Admin;

use FSR\Cso;
use FSR\Donor;
use FSR\FoodType;
use FSR\Location;
use FSR\Region;
use FSR\Volunteer;
use FSR\Organization;
use FSR\QuantityType;
use FSR\Custom\Methods;
use FSR\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NewRegionController extends Controller
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
    public function index()
    {
        return view('admin.new_region');
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
        if ($validation->fails()) {
            return back()->withErrors($validation->errors())
                                                   ->withInput();
        }

        $region = $this->create($request->all());

        return back()->with('status', "Регионот е додаден успешно!");
    }

    /**
     * Create a new Region instance.
     *
     * @param  array  $data
     * @return \FSR\Region
     */
    protected function create(array $data)
    {
        return  Region::create([
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
