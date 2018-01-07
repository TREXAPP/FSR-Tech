<?php

namespace FSR\Http\Controllers\Donor;

use FSR\Listing;
use FSR\ListingOffer;
use FSR\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MyAcceptedListingsController extends Controller
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
     * Show a shigle listing offer
     * @param Request
     * @param int $listing_offer_id
     * @return void
     */
    public function single_listing_offer(Request $request, $listing_offer_id = null)
    {
        $listing_offer = ListingOffer::where('offer_status', 'active')
                                    ->whereHas('listing', function ($query) {
                                        $query->where('date_expires', '>', Carbon::now()->format('Y-m-d H:i'))
                                              ->where('donor_id', Auth::user()->id)
                                              ->where('listing_status', 'active');
                                    })->find($listing_offer_id);


        if ($listing_offer) {
            return view('donor.my_accepted_listings')->with([
            'listing_offer' => $listing_offer,
          ]);
        } else {
            //not ok, show error page
        }
    }
}
