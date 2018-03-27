<?php

namespace FSR\Http\Controllers\Admin;

use FSR;
use FSR\File;
use FSR\Listing;
use FSR\Product;
use FSR\FoodType;
use FSR\Location;
use FSR\ListingOffer;
use FSR\QuantityType;
use FSR\ProductsQuantityType;
use FSR\Custom\Methods;

use FSR\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

class EditProductController extends Controller
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
    public function index(Request $request, string $product_id_string)
    {
        $product_id = ctype_digit($product_id_string) ? intval($product_id_string) : null;
        if ($product_id === null) {
            return redirect(route('admin.products'));
        } else {
            $product = Product::find($product_id);
            if (!$product) {
                return redirect(route('admin.products'));
            } else {
                $food_types = FoodType::where('status', 'active')->get();
                $quantity_types = QuantityType::where('status', 'active')->get();
                return view('admin.edit_product')->with([
              'product' => $product,
              'food_types' => $food_types,
              'quantity_types' => $quantity_types,
          ]);
            }
        }
    }

    /**
     * Get a validator for a new product edit
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
     * Handle "edit product". - post
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle_post(Request $request)
    {
        $product_id = $request->all()['product_id'];
        $product = Product::find($product_id);

        if ($this->change_detected($request, $product)) {
            $validation = $this->validator($request->all());


            if ($validation->fails()) {
                return back()->withErrors($validation->errors())
                                                 ->withInput();
            }

            $product = $this->update($product, $request->all());
            return redirect(route('admin.products'))->with('status', "Измените се успешно зачувани!");
        } else {
            return back();
        }
    }

    /**
     * Indicates if changes are being made to the product information
     *
     * @param  Request  $request
     * @param  Product $product
     * @return bool
     */
    protected function change_detected(Request $request, $product)
    {

        //not doing this check .. for now .. too complicated
        // $data = $request->all();
        //
        // if ($product->name != $data['name']) {
        //     return true;
        // }
        // if ($product->description != $data['description']) {
        //     return true;
        // }


        //
        // foreach ($product->quantity_types as $product_quantity_type) {
        //   for ($i=1; $i < $data['number_of_quantity_types']+1; $i++) {
        //     if () // i tuka zaglavivme ...
        //   }
        //   $product_quantity_type
        // }

        //  return false;

        return true;
    }

    /**
     * updates the information for the profile
     *
     * @param  Product $product
     * @param  array  $data
     * @return FSR\Product $product
     */
    protected function update(Product $product, array $data)
    {
        $product->food_type_id = $data['food_type'];
        $product->name = $data['name'];
        $product->description = $data['description'];

        foreach ($product->quantity_types as $quantity_type) {
            $quantity_type->pivot->delete();
        }
        $product->save();
        $this->create_products_quantity_types($data, $product->id);

        return $product;
    }


    /**
     * Create a all the products_quantity_types instances.
     *
     * @param  array  $data
     * @param  int  $product_id
     * @return \FSR\Organization
     */
    public function create_products_quantity_types($data, $product_id)
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
}
