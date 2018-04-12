<?php

namespace FSR\Http\Controllers\Admin;

use FSR;
use FSR\File;
use FSR\Listing;
use FSR\Product;
use FSR\FoodType;
use FSR\QuantityType;
use FSR\ListingOffer;
use FSR\Custom\Methods;

use FSR\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

class EditListingController extends Controller
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
    public function index(Request $request, string $listing_id_string)
    {
        //TODO - $max_accepted_quantity
        //products
        //food types
        $listing_id = ctype_digit($listing_id_string) ? intval($listing_id_string) : null;
        if ($listing_id === null) {
            return redirect(route('admin.listings'));
        } else {
            $listing = Listing::find($listing_id);
            if (!$listing) {
                return redirect(route('admin.listings'));
            } else {
                $max_accepted_quantity = 0;
                foreach ($listing->listing_offers as $listing_offer) {
                    if ($listing_offer->status == 'active') {
                        $max_accepted_quantity += $listing_offer->quantity;
                    }
                }
                $products = Product::where('status', 'active')
                                   ->where('food_type_id', $listing->product->food_type->id);
                $food_types = FoodType::where('status', 'active')
                                      ->where('food_type_id', $listing->product->food_type->id);
                return view('admin.edit_listing')->with([
              'listing' => $listing,
              'max_accepted_quantity' => $max_accepted_quantity,
              'products' => $products,
              'food_types' => $food_types,
          ]);
            }
        }
    }
    //
    // /**
    //  * Get a validator for a new quantity_type insert
    //  *
    //  * @param  array  $data
    //  * @return \Illuminate\Contracts\Validation\Validator
    //  */
    // protected function validator(array $data)
    // {
    //     $validatorArray = [
    //                 'description'    => 'required',
    //             ];
    //
    //     return Validator::make($data, $validatorArray);
    // }
    //
    // /**
    //  * Handle "edit quantity_type". - post
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function handle_post(Request $request)
    // {
    //     $quantity_type_id = $request->all()['quantity_type_id'];
    //     $quantity_type = QuantityType::find($quantity_type_id);
    //
    //     if ($this->change_detected($request, $quantity_type)) {
    //         $validation = $this->validator($request->all());
    //
    //
    //         if ($validation->fails()) {
    //             return back()->withErrors($validation->errors())
    //                                              ->withInput();
    //         }
    //
    //         $quantity_type = $this->update($quantity_type, $request->all());
    //         return redirect(route('admin.quantity_types'))->with('status', "Измените се успешно зачувани!");
    //     } else {
    //         return back();
    //     }
    // }
    //
    // /**
    //  * Indicates if changes are being made to the quantity_type information
    //  *
    //  * @param  Request  $request
    //  * @param  QuantityType $quantity_type
    //  * @return bool
    //  */
    // protected function change_detected(Request $request, $quantity_type)
    // {
    //     $data = $request->all();
    //
    //     if ($quantity_type->name != $data['name']) {
    //         return true;
    //     }
    //     if ($quantity_type->description != $data['description']) {
    //         return true;
    //     }
    //
    //     return false;
    // }
    //
    // /**
    //  * updates the information for the profile
    //  *
    //  * @param  QuantityType $quantity_type
    //  * @param  array  $data
    //  * @return FSR\QuantityType $quantity_type
    //  */
    // protected function update(QuantityType $quantity_type, array $data)
    // {
    //     $quantity_type->name = $data['name'];
    //     $quantity_type->description = $data['description'];
    //
    //     $quantity_type->save();
    //
    //     return $quantity_type;
    // }
}
