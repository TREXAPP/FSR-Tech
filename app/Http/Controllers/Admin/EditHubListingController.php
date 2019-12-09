<?php

namespace FSR\Http\Controllers\Admin;

use FSR;
use FSR\File;
use FSR\HubListing;
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

class EditHubListingController extends Controller
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
    public function index(Request $request, string $hub_listing_id_string)
    {
        $hub_listing_id = ctype_digit($hub_listing_id_string) ? intval($hub_listing_id_string) : null;
        if ($hub_listing_id === null) {
            return redirect(route('admin.hub_listings'));
        } else {
            $hub_listing = HubListing::find($hub_listing_id);
            if (!$hub_listing) {
                return redirect(route('admin.hub_listings'));
            } else {
                $max_accepted_quantity = 0;
                foreach ($hub_listing->listing_offers as $listing_offer) {
                    if ($listing_offer->offer_status == 'active') {
                        $max_accepted_quantity += $listing_offer->quantity;
                    }
                }
                $products = Product::where('status', 'active')
                                   ->where('food_type_id', $hub_listing->product->food_type->id)->get();
                $food_types = FoodType::where('status', 'active')->get();
                return view('admin.edit_hub_listing')->with([
              'hub_listing' => $hub_listing,
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

        $hub_listing_id = $data['hub_listing_id'];
        $hub_listing = HubListing::find($hub_listing_id);
        $change = $this->change_detected($data, $hub_listing);
        if ($this->change_detected($data, $hub_listing)) {
            $validation = $this->validator($data);

            if ($validation->fails()) {
                return back()->withErrors($validation->errors())
                                               ->withInput();
            }
            $hub_listing = $this->update($hub_listing, $data);
            return back()->with('status', "Донацијата е изменета успешно!");
        } else {
            return back();
        }
    }


    /**
     * Indicates if changes are being made to the listing information
     *
     * @param  Request  $request
     * @param  HubListing $hub_listing
     * @return bool
     */
    protected function change_detected(array $data, HubListing $hub_listing)
    {
        if ($hub_listing->product_id != $data['product_id']) {
            return true;
        }
        if ($hub_listing->description != $data['description']) {
            return true;
        }
        if ($hub_listing->quantity != $data['quantity']) {
            return true;
        }
        if ($hub_listing->quantity_type_id != $data['quantity_type']) {
            return true;
        }
        if ($hub_listing->date_listed != Methods::convert_date_input_to_db($data['date_listed'])) {
            return true;
        }
        if ($hub_listing->sell_by_date != $data['sell_by_date']) {
            return true;
        }
        if ($hub_listing->date_expires != Methods::convert_date_input_to_db($data['date_expires'])) {
            return true;
        }
        if ($hub_listing->pickup_time_from != $data['pickup_time_from']) {
            return true;
        }
        if ($hub_listing->pickup_time_to != $data['pickup_time_to']) {
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
    protected function update(HubListing $hub_listing, array $data)
    {
        $hub_listing->product_id = $data['product_id'];
        $hub_listing->description = $data['description'];
        $hub_listing->quantity = $data['quantity'];
        $hub_listing->quantity_type_id = $data['quantity_type'];
        $hub_listing->date_listed = $data['date_listed'];
        $hub_listing->sell_by_date = $data['sell_by_date'];
        $hub_listing->date_expires = $data['date_expires'];
        $hub_listing->pickup_time_from = $data['pickup_time_from'];
        $hub_listing->pickup_time_to = $data['pickup_time_to'];

        $hub_listing->save();

        return $hub_listing;
    }
}
