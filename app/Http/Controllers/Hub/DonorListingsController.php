<?php

namespace FSR\Http\Controllers\Hub;

use FSR\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use FSR\Custom\Methods;
use Illuminate\Support\Carbon;
use FSR\Listing;
use FSR\HubListing;
use FSR\Hub;
use FSR\Cso;
use FSR\Admin;
use FSR\HubListingOffer;
use Illuminate\Support\Facades\Validator;
use FSR\Notifications\HubToDonorAcceptDonation;
use FSR\Notifications\HubToAdminAcceptDonation;
use FSR\Notifications\HubToHubAcceptDonation;
use FSR\Notifications\HubToCsosAdminNewDonation;
use Illuminate\Support\Facades\Notification;

class DonorListingsController extends Controller
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
        Methods::log_event('open_home_page', Auth::user()->id, 'hub');
        $donor_listings = Listing::where('date_expires', '>', Carbon::now()->format('Y-m-d H:i'))
            ->where('date_listed', '<=', Carbon::now()->format('Y-m-d H:i'))
            ->where('listing_status', 'active')
            ->orderBy('date_expires', 'ASC');

        $hub_listing_offers = HubListingOffer::where('status', 'active')->get();
        $donor_listings_no = 0;
        foreach ($donor_listings->get() as $donor_listing) {
            $quantity_counter = 0;
            foreach ($donor_listing->hub_listing_offers as $hub_listing_offer) {
                if ($hub_listing_offer->status == 'active') {
                    $quantity_counter += $hub_listing_offer->quantity;
                }
            }
            if ($donor_listing->quantity > $quantity_counter) {
                $donor_listings_no++;
            }
        }
        return view('hub.donor_listings')->with([
            'donor_listings' => $donor_listings,
            'hub_listing_offers' => $hub_listing_offers,
            'donor_listings_no' => $donor_listings_no,
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
        $reposting = $request->all()["checkbox_reposted"] === 'true';

        //  http://fsr.test/cso/active_listings#listingbox6
        $route = route('hub.donor_listings') . '#listingbox' . $request->all()['listing_id'];

        if ($validation->fails()) {
            return redirect($route)->withErrors($validation->errors())
                ->withInput();
        }

        if ($reposting) {
            $new_listing_validation = $this->validator_listing($request->all());
            if ($new_listing_validation->fails()) {
                return redirect($route)->withErrors($new_listing_validation->errors())
                    ->withInput();
            }
        }

        $hub_listing_offer = $this->create($request->all());
        $status_label = "Донацијата е успешно прифатена!";

        if ($reposting) {
            $hub_listing = $this->create_listing($request->all());
            $status_label = "Донацијата е успешно прифатена и објавена!";
        }

        $hub = Auth::user();
        $donor = $hub_listing_offer->listing->donor;

        $donor->notify(new HubToDonorAcceptDonation($hub_listing_offer));
        $hub->notify(new HubToHubAcceptDonation($hub_listing_offer, $reposting, $hub_listing));

        $master_admins = Admin::where('master_admin', 1)
                          ->where('status', 'active')->get();
        Notification::send($master_admins, new HubToAdminAcceptDonation($hub_listing_offer, $hub, $donor, $reposting, $hub_listing));

        if ($reposting) {
            $hub_region_id = $hub->region_id;
            $csos = Cso::where('status', 'active')
                        ->whereHas('location', function ($query) use ($hub_region_id) {
                            $query->where('region_id', $hub_region_id);
                        })->get();
            Notification::send($csos, new HubToCsosAdminNewDonation($hub_listing));
        }
        return back()->with('status', $status_label);
    }

    /**
     * Create a new listing_offer instance after a valid input.
     *
     * @param  array  $data
     * @return \FSR\HubListingOffer
     */
    protected function create(array $data)
    {
        return HubListingOffer::create([
            'hub_id' => Auth::user()->id,
            'listing_id' => $data['listing_id'],
            'status' => 'active',
            'quantity' => $data['quantity'],
        ]);
    }
    
    /**
     * Create a new listing_offer instance after a valid input.
     *
     * @param  array  $data
     * @return \FSR\HubListingOffer
     */
    protected function create_listing(array $data)
    {
        return HubListing::create([
            'hub_id' => Auth::user()->id,
            'product_id' => $data['product_id'],
            'food_type_id' => $data['food_type'],
            'description' => $data['description_reposted'],
            'quantity' => $data['quantity_reposted'],
            'quantity_type_id' => $data['quantity_type_id'],
            'date_listed' => Carbon::now()->format('Y-m-d H:i'),
            'date_expires' => $this->calculate_date_carbon(Carbon::now()->format('Y-m-d H:i'), $data['expires_in_reposted'], $data['time_type_reposted']),
            'sell_by_date' => $data['sell_by_date'],
            'pickup_time_from' => '09:00:00',
            'pickup_time_to' => '17:00:00',
            'status' => 'active',
        ]);
    }

    /**
     * Get a validator for an incoming listing offer input request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $listing = Listing::find($data['listing_id']);
        $hub_listing_offers = $listing->hub_listing_offers;
        $quantity_counter = 0;
        foreach ($hub_listing_offers as $hub_listing_offer) {
            if ($hub_listing_offer->status == 'active') {
                $quantity_counter += $hub_listing_offer->quantity;
            }
        }
        $max_quantity = $listing->quantity - $quantity_counter;

        $validatorArray = [
            'listing_id' => 'required',
            'quantity' => 'required|numeric|min:0.01|max:' . $max_quantity,
        ];

        return Validator::make($data, $validatorArray);
    }
    
    /**
     * Get a validator for an incoming listing offer input request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator_listing(array $data)
    {

        $validatorArray = [
            'expires_in_reposted' => 'required|numeric|custom_before_date_and_now:time_type_reposted,sell_by_date',
            'quantity_reposted'   => 'required|numeric|min:0.01|max:' . $data['quantity'],
        ];

        return Validator::make($data, $validatorArray);
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
