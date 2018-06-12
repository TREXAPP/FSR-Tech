<?php

namespace FSR\Http\Controllers\Donor;

use FSR\Listing;
use FSR\ListingOffer;
use FSR\Custom\Methods;

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
        Methods::log_event('open_home_page', Auth::user()->id, 'donor');
        //  $active_listings = Listing::where('listing_status', 'active');
        $active_listings = Listing::where('date_expires', '>', Carbon::now()->format('Y-m-d H:i'))
                                  ->where('listing_status', 'active')
                                  ->where('donor_id', Auth::user()->id)
                                  ->withCount('listing_offers')
                                  ->withCount(['listing_offers' => function ($query) {
                                      $query->where('offer_status', 'active');
                                  }])
                                  ->orderBy('date_expires', 'ASC');

        return view('donor.my_active_listings')->with([
          'active_listings' => $active_listings,
        ]);
    }
}
