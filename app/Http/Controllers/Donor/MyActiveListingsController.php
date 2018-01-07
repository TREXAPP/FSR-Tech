<?php

namespace FSR\Http\Controllers\Donor;

use FSR\Listing;
use FSR\ListingOffer;
use FSR\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MyActiveListingsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:donor');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //  $active_listings = Listing::where('listing_status', 'active');
        $active_listings = Listing::where('date_expires', '>', Carbon::now()->format('Y-m-d H:i'))
                                  ->where('listing_status', 'active')
                                  ->where('donor_id', Auth::user()->id)
                                  ->withCount('listing_offers')
                                  ->withCount(['listing_offers' => function ($query) {
                                      $query->where('offer_status', 'active');
                                  }])
                                  ->orderBy('date_expires', 'ASC');

        $active_listings_no = 0;
        foreach ($active_listings->get() as $active_listing) {
            //     $quantity_counter = 0;
            //     foreach ($active_listing->listing_offers as $listing_offer) {
            //         if ($listing_offer->offer_status == 'active') {
            //             $quantity_counter += $listing_offer->quantity;
            //         }
            //     }
            //     if ($active_listing->quantity > $quantity_counter) {
            $active_listings_no++;
            //     }
        }

        return view('donor.my_active_listings')->with([
          'active_listings' => $active_listings,
          // 'listing_offers' => $listing_offers,
          'active_listings_no' => $active_listings_no,
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
        $route = route('cso.active_listings') . '#listingbox' . $request->all()['listing_id'];

        if ($validation->fails()) {
            return redirect($route)->withErrors($validation->errors())
                                   ->withInput();
        }
        $listing_offer = $this->create($request->all());
        return back()->with('status', "Донацијата е успешно прифатена!");
    }

    /**
     * Create a new listing_offer instance after a valid input.
     *
     * @param  array  $data
     * @return \FSR\ListingOffer
     */
    protected function create(array $data)
    {
        return  ListingOffer::create([
                'cso_id' => Auth::user()->id,
                'listing_id' => $data['listing_id'],
                'offer_status' => 'active',
                'quantity' => $data['quantity'],
                'beneficiaries_no' => $data['beneficiaries'],
                'volunteer_pickup_name' => $data['volunteer_name'],
                'volunteer_pickup_phone' => $data['volunteer_phone'],
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
        $listing_offers = $listing->listing_offers;
        $quantity_counter = 0;
        foreach ($listing_offers as $listing_offer) {
            $quantity_counter += $listing_offer->quantity;
        }
        $max_quantity = $listing->quantity - $quantity_counter;

        $validatorArray = [
            'listing_id'         => 'required',
            'quantity'           => 'required|numeric|min:1|max:' . $max_quantity,
            'beneficiaries'      => 'required|numeric',
            'volunteer_name'     => 'required',
            'volunteer_phone'    => 'required',
        ];

        return Validator::make($data, $validatorArray);
    }
}
