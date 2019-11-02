<?php

namespace FSR\Http\Controllers\Hub;

use FSR\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use FSR\Custom\Methods;
use Illuminate\Support\Carbon;
use FSR\Listing;
use FSR\Hub;
use FSR\HubListingOffer;
use Illuminate\Support\Facades\Validator;

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

        //  http://fsr.test/cso/active_listings#listingbox6
        $route = route('hub.donor_listings') . '#listingbox' . $request->all()['listing_id'];

        if ($validation->fails()) {
            return redirect($route)->withErrors($validation->errors())
                ->withInput();
        }

        $hub_listing_offer = $this->create($request->all());
        $hub = Auth::user();
        $donor = $hub_listing_offer->listing->donor;

        // TODO: kreiraj 3 notifikacii:

        // $donor->notify(new HubToDonorAcceptDonation($hub_listing_offer));
        // $hub->notify(new HubToCsoAcceptDonation($hub_listing_offer));

        // $master_admins = Admin::where('master_admin', 1)
        //                   ->where('status', 'active')->get();
        // Notification::send($master_admins, new HubToAdminAcceptDonation($hub_listing_offer, $hub, $donor));

        return back()->with('status', "Донацијата е успешно прифатена!");
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
                $quantity_counter += $listing_offer->quantity;
            }
        }
        $max_quantity = $listing->quantity - $quantity_counter;

        $validatorArray = [
            'listing_id' => 'required',
            'quantity' => 'required|numeric|min:0.01|max:' . $max_quantity,
        ];

        return Validator::make($data, $validatorArray);
    }
}
