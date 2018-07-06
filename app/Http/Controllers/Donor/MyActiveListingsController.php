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

        $date_from = substr(Carbon::now()->addDays(-90), 0, 10);
        $date_to = substr(Carbon::now(), 0, 10);
        $selected_filter = 'active';

        //  $listings = Listing::where('listing_status', 'active');
        $listings = Listing::where('date_expires', '>', Carbon::now()->format('Y-m-d H:i'))
                                  ->where('listing_status', 'active')
                                  ->where('donor_id', Auth::user()->id)
                                  ->withCount('listing_offers')
                                  ->withCount(['listing_offers' => function ($query) {
                                      $query->where('offer_status', 'active');
                                  }])
                                  ->orderBy('date_expires', 'ASC');

        return view('donor.my_active_listings')->with([
          'listings' => $listings,
          'date_from' => $date_from,
          'date_to' => $date_to,
          'selected_filter' => $selected_filter,
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
        $data = $request->all();
        if (!empty($data['post-type'])) {
            switch ($data['post-type']) {

            case 'filter':
              return $this->handle_filter($data);
            default:
              return $this->index();
            break;
          }
        }
    }

    /**
     * Handle offer listing "filter".
     *
     * @param  Array $data
     * @return \Illuminate\Http\Response
     */
    public function handle_filter(array $data)
    {
        $date_from = $data["filter_date_from"];
        $date_to = $data["filter_date_to"];
        $selected_filter = $data["donations-filter-select"];
        switch ($selected_filter) {
          case 'active':
            $listing_status_operator = ">";
            break;
          case 'past':
            $listing_status_operator = "<";
            break;

          default:
            $listing_status_operator = ">";
            break;
        }
        $listings = Listing::where('date_expires', $listing_status_operator, Carbon::now()->format('Y-m-d H:i'))
                                ->where('date_listed', '>=', $date_from)
                                ->where('date_listed', '<=', $date_to)
                                ->where('donor_id', Auth::user()->id)
                                ->where('listing_status', 'active')
                                ->withCount('listing_offers')
                                ->withCount(['listing_offers' => function ($query) {
                                    $query->where('offer_status', 'active');
                                }])
                                ->orderBy('date_expires', 'ASC');

        return view('donor.my_active_listings')->with([
        'listings' => $listings,
        'date_from' => $date_from,
        'date_to' => $date_to,
        'selected_filter' => $selected_filter,
      ]);
    }
}
