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
        $this->middleware('auth:master_admin,admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, string $listing_id_string)
    {
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
                    if ($listing_offer->offer_status == 'active') {
                        $max_accepted_quantity += $listing_offer->quantity;
                    }
                }
                $products = Product::where('status', 'active')
                                   ->where('food_type_id', $listing->product->food_type->id)->get();
                $food_types = FoodType::where('status', 'active')->get();
                return view('admin.edit_listing')->with([
              'listing' => $listing,
              'max_accepted_quantity' => $max_accepted_quantity,
              'products' => $products,
              'food_types' => $food_types,
          ]);
            }
        }
    }

    /**
     * Retrieve Products with ajax to populate the <select> control
     *
     * @param  Illuminate\Http\Request $request
     * @return Collection
     */
    public function getProducts(Request $request)
    {
        return $products = Product::where('food_type_id', $request->input('food_type'))
                                  ->where('status', 'active')->get();
    }

    /**
     * Retrieve Products with ajax to populate the <select> control
     *
     * @param  Illuminate\Http\Request $request
     * @return Collection
     */
    public function getQuantityTypes(Request $request)
    {
        return $quantity_types = Product::find($request->input('product_id'))->quantity_types;
        // return $quantity_types = QuantityType::where('product_id', $request->input('product_id'))
        //                           ->where('status', 'active')->get();
    }


    /**
     * Get a validator for edit donation.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $validatorArray = [
                'food_type'           => 'required',
                'product_id'          => 'required',
                'quantity'            => 'required|numeric',
                'quantity_type'       => 'required',
                'date_listed'         => 'required',
                'sell_by_date_compare'=> 'required|date|after_or_equal:date_listed',
                'date_expires'        => 'required|date|after_or_equal:date_listed|before_or_equal:sell_by_date_compare',
                'pickup_time_from'    => 'required',
                'pickup_time_to'      => 'required',
            ];

        return Validator::make($data, $validatorArray);
    }


    /**
     * Handle edit listing request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle_post(Request $request)
    {
        $data = $request->all();
        $sell_by_date_compare = Carbon::parse($data['sell_by_date'])->addDays(1);
        $data['sell_by_date_compare'] = $sell_by_date_compare->format('Y-m-d');

        $listing_id = $data['listing_id'];
        $listing = Listing::find($listing_id);
        $change = $this->change_detected($data, $listing);
        if ($this->change_detected($data, $listing)) {
            $validation = $this->validator($data);

            if ($validation->fails()) {
                return back()->withErrors($validation->errors())
                                               ->withInput();
            }
            $listing = $this->update($listing, $data);
            return back()->with('status', "Донацијата е изменета успешно!");
        } else {
            return back();
        }
    }


    /**
     * Indicates if changes are being made to the listing information
     *
     * @param  Request  $request
     * @param  Listing $listing
     * @return bool
     */
    protected function change_detected(array $data, Listing $listing)
    {
        if ($listing->product_id != $data['product_id']) {
            return true;
        }
        if ($listing->description != $data['description']) {
            return true;
        }
        if ($listing->quantity != $data['quantity']) {
            return true;
        }
        if ($listing->quantity_type_id != $data['quantity_type']) {
            return true;
        }
        if ($listing->date_listed != Methods::convert_date_input_to_db($data['date_listed'])) {
            return true;
        }
        if ($listing->sell_by_date != $data['sell_by_date']) {
            return true;
        }
        if ($listing->date_expires != Methods::convert_date_input_to_db($data['date_expires'])) {
            return true;
        }
        if ($listing->pickup_time_from != $data['pickup_time_from']) {
            return true;
        }
        if ($listing->pickup_time_to != $data['pickup_time_to']) {
            return true;
        }
        return false;
    }

    /**
     * updates the information for the listing
     *
     * @param  Listing $listing
     * @param  array  $data
     * @param  int  $file_id
     * @return FSR\Listing $listing
     */
    protected function update(Listing $listing, array $data)
    {
        $listing->product_id = $data['product_id'];
        $listing->description = $data['description'];
        $listing->quantity = $data['quantity'];
        $listing->quantity_type_id = $data['quantity_type'];
        $listing->date_listed = $data['date_listed'];
        $listing->sell_by_date = $data['sell_by_date'];
        $listing->date_expires = $data['date_expires'];
        $listing->pickup_time_from = $data['pickup_time_from'];
        $listing->pickup_time_to = $data['pickup_time_to'];

        $listing->save();

        return $listing;
    }
}
