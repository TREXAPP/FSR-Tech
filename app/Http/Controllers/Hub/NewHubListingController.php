<?php

namespace FSR\Http\Controllers\Hub;

use Carbon\Carbon;
use FSR\File;
use FSR\Cso;
use FSR\Admin;
use FSR\HubListing;
use FSR\Product;
use FSR\FoodType;
use FSR\QuantityType;
use FSR\Custom\Methods;
use FSR\Notifications\HubToCsosAdminNewDonation;
use Illuminate\Http\Request;
use FSR\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use Intervention\Image\ImageManagerStatic as Image;

class NewHubListingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:hub');
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $food_type = old('food_type');
        if ($food_type) {
            $products = Product::where('food_type_id', $food_type)
                               ->where('status', 'active')->get();
        } else {
            $products = null;
        }
        $product = old('product_id');
        if ($product) {
            $quantity_types = Product::find($product)->quantity_types;
        } else {
            $quantity_types = null;
        }

        $food_types = FoodType::where('status', 'active')->get();
        $now = Carbon::now()->format('Y-m-d') . 'T' . Carbon::now()->format('H:i');
        return view('hub.new_hub_listing')->with([
          'quantity_types' => $quantity_types,
          'food_types' => $food_types,
          'products' => $products,
          'now' => $now,
        ]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $validatorArray = [
            'food_type'         => 'required',
            'product_id'        => 'required',
            'image'             => 'image|max:2048',
            'quantity'          => 'required|numeric',
            'quantity_type'     => 'required',
            'date_listed'       => 'required',
            'sell_by_date'      => 'required|numeric|custom_greater_than_date:date_listed,time_type_sell_by',
            'expires_in'        => 'required|numeric|custom_between_dates:date_listed,sell_by_date,time_type_sell_by,time_type',
            'time_type'         => 'required',
            'pickup_time_from'  => 'required',
            'pickup_time_to'    => 'required',
        ];

        return Validator::make($data, $validatorArray);
    }


    /**
     * Handle a "add new hub_listing" request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle_post(Request $request)
    {

        // $date_expires_validator = $this->validate_date_expires($request->all());
        // if ($date_expires_validator) {
        //
        // }
        $validation = $this->validator($request->all());
        //$this->validator($request->all())->validate();
        if ($validation->fails()) {
            return redirect(route('hub.new_hub_listing'))->withErrors($validation->errors())
                                                     ->withInput();
        }

        $file_id = $this->new_hub_listing_handle_upload($request);
        $hub_listing = $this->create($request->all(), $file_id);

        $hub_region_id = Auth::user()->region_id;
        $csos = Cso::where('status', 'active')
                    ->whereHas('location', function ($query) use ($hub_region_id) {
                        $query->where('region_id', $hub_region_id);
                    })->get();

       Notification::send($csos, new HubToCsosAdminNewDonation($hub_listing));

        $master_admins = Admin::where('master_admin', 1)->where('status', 'active')->get();
        Notification::send($master_admins, new HubToCsosAdminNewDonation($hub_listing));

        return back()->with('status', "Донацијата е додадена успешно!");
    }

    /**
     * set information for image upload for adding new volunteer
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function new_hub_listing_handle_upload(Request $request)
    {
        $input_name = 'image';
        $purpose = 'listing image';
        $for_user_type = 'hub';
        $description = 'An uploaded image for a listing put up by a hub.';

        return Methods::handleUpload($request, $input_name, $purpose, $for_user_type, $description);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \FSR\User
     */
    protected function create(array $data, $file_id)
    {
        return  HubListing::create([
                'hub_id' => Auth::user()->id,
                'product_id' => $data['product_id'],
                'description' => $data['description'],
                'food_type_id' => $data['food_type'],
                'quantity' => $data['quantity'],
                'quantity_type_id' => $data['quantity_type'],
                'date_listed' => Methods::convert_date_input_to_db($data['date_listed']),
                'date_expires' => $this->calculate_date_carbon($data['date_listed'], $data['expires_in'], $data['time_type']),
                'sell_by_date' => $this->calculate_date_carbon($data['date_listed'], $data['sell_by_date'], $data['time_type_sell_by']),
                'image_id' => $file_id,
                'pickup_time_from' => $data['pickup_time_from'],
                'pickup_time_to' => $data['pickup_time_to'],
                'status' => 'active',

            ]);
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
     * Retrieve Quantity Types with ajax to populate the <select> control
     *
     * @param  Illuminate\Http\Request $request
     * @return Collection
     */
    public function getQuantityTypes(Request $request)
    {
        //$response = Product::find($request->input('product'))->quantity_types;

        return $quantity_types = Product::find($request->input('product'))->quantity_types;
        //return $product = Product::find($request->input('product'))->get();
    }

    /**
     * Calculates the datetime when a listing expires, from the different input values
     *
     * @param string $date_listed is the starting datetime of the listing
     * @param int $time_value specifies how much of the $time_type the listing will stay as active
     * @param string $time_type can be hours, days or weeks
     * @return string
     */
    public function calculate_date_carbon($date_listed, $time_value, $time_type)
    {
        $carbon_date = new Carbon($date_listed);

        switch ($time_type) {
        case 'hours':
          return $carbon_date->addHours($time_value)->format('Y-m-d H:i');
          break;
        case 'days':
                return $carbon_date->addDays($time_value)->format('Y-m-d H:i');
          break;
        case 'weeks':
                return $carbon_date->addWeeks($time_value)->format('Y-m-d H:i');
          break;

        default:
          return $carbon_date->addHours($time_value)->format('Y-m-d H:i');
          break;
      }
    }
}
