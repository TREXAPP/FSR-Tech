<?php

namespace FSR\Http\Controllers\Admin;

use FSR\Cso;
use FSR\Donor;
use FSR\Product;
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

class NewProductController extends Controller
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
        $food_types = FoodType::all();
        return view('admin.new_product')->with([
          'food_types' => $food_types
        ]);
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

        //$file_id = $this->new_product_handle_upload($request);
        $product = $this->create($request->all());

        return back()->with('status', "Производот е додаден успешно!");
    }

    /**
     * Create a new product instance.
     *
     * @param  array  $data
     * @return \FSR\Organization
     */
    protected function create(array $data)
    {
        return  Product::create([
                'name' => $data['name'],
                'description' => $data['description'],
                'food_type_id' => $data['food_type'],
            ]);
    }

    /**
     * Get a validator for a new product.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $validatorArray = [
            'name'                    => 'required',
            'food_type'               => 'required',
        ];

        return Validator::make($data, $validatorArray);
    }

    /**
     * set information for image upload for adding new organization
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // protected function new_organization_handle_upload(Request $request)
    // {
    //     $input_name = 'image';
    //     $purpose = 'organization image';
    //     $for_user_type = $request->all()['type'];
    //     $description = 'An uploaded image for a new organization.';
    //
    //     return Methods::handleUpload($request, $input_name, $purpose, $for_user_type, $description);
    // }
}
