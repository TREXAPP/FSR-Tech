<?php

namespace FSR\Http\Controllers\Admin;

use FSR\Cso;
use FSR\Donor;
use FSR\Product;
use FSR\FoodType;
use FSR\Volunteer;
use FSR\Organization;
use FSR\QuantityType;
use FSR\ProductsQuantityType;
use FSR\Custom\Methods;
use FSR\Http\Controllers\Controller;
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
        $this->middleware('auth:master_admin,admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $food_types = FoodType::where('status', 'active')->get();
        $quantity_types = QuantityType::where('status', 'active')->get();

        return view('admin.new_product')->with([
          'food_types' => $food_types,
          'quantity_types' => $quantity_types,
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
        $this->create_products_quantity_types($request->all(), $product->id);


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
     * Create a all the products_quantity_types instances.
     *
     * @param  array  $data
     * @param  int  $product_id
     * @return \FSR\Organization
     */
    protected function create_products_quantity_types(array $data, $product_id)
    {
        if ($data['number_of_quantity_types']) {
            for ($count=1; $count <= $data['number_of_quantity_types']; $count++) {
                if ($count == $data['quantity_type_default']) {
                    $default = 1;
                } else {
                    $default = 0;
                }

                ProductsQuantityType::create([
                  'product_id' => $product_id,
                  'quantity_type_id' => $data['quantity_type_' . $count],
                  'default' => $default,
                  'portion_size' => $data['portion_size_' . $count],
                ]);
            }
        }
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
