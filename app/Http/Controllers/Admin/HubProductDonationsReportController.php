<?php

namespace FSR\Http\Controllers\Admin;

use FSR\Cso;
use FSR\Log;
use FSR\Hub;
use FSR\HubListing;
use FSR\Product;
use FSR\ListingOffer;
use FSR\Organization;
use FSR\Custom\Methods;

use FSR\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HubProductDonationsReportController extends Controller
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
    public function index()
    {
        $data["filter_date_from"] = substr(Carbon::now()->addDays(-90), 0, 10);
        $data["filter_date_to"] = substr(Carbon::now(), 0, 10);
        return $this->handle_filter($data);
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
        $date_to_date = Carbon::parse($date_to)->addDays(1);

        $products = Product::where('status', 'active')
                           ->orderBy('food_type_id')->get();

        $hub_listings = HubListing::where('date_listed', '>=', $date_from)
                        ->where('date_listed', '<=', $date_to_date)
                        ->where('status', 'active')
                        ->orderBy('quantity_type_id')->get();

        return view('admin.hub_product_donations_report')->with([
          'date_from' => $date_from,
          'date_to' => $date_to,
          'products' => $products,
          'hub_listings' => $hub_listings,
        ]);
    }
}
