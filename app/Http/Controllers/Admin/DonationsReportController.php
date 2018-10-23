<?php

namespace FSR\Http\Controllers\Admin;

use FSR\Cso;
use FSR\Log;
use FSR\Donor;
use FSR\Listing;
use FSR\Product;
use FSR\FoodType;
use FSR\ListingOffer;
use FSR\Organization;
use FSR\Custom\Methods;

use FSR\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DonationsReportController extends Controller
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
        $data["filter_date_from"] = substr(Carbon::now()->addDays(-390), 0, 10);
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
        //dd($data);
        $date_from = $data["filter_date_from"];
        $date_to = $data["filter_date_to"];

        $listing_offers = ListingOffer::where('offer_status', 'active')
                                    ->whereHas('listing', function ($query) use ($date_from, $date_to) {
                                        $query->where('date_listed', '>=', $date_from)
                                            ->where('date_listed', '<=', $date_to);
                                    });

        if (isset($data["filter_cso_organization"])) {
            $filter_cso_organization = $data["filter_cso_organization"];
            $listing_offers = $listing_offers->whereHas('cso', function ($query) use ($filter_cso_organization) {
                $query->where('organization_id', '=', $filter_cso_organization);
            });
        }

        if (isset($data["filter_donor_organization"])) {
            $filter_donor_organization = $data["filter_donor_organization"];
            $listing_offers = $listing_offers->whereHas('listing', function ($query) use ($filter_donor_organization) {
                $query->whereHas('donor', function ($query2) use ($filter_donor_organization) {
                    $query2->where('organization_id', '=', $filter_donor_organization);
                });
            });
        }

        if (isset($data["filter_product"])) {
            $filter_product = $data["filter_product"];
            $listing_offers = $listing_offers->whereHas('listing', function ($query) use ($filter_product) {
                $query->where('product_id', '=', $filter_product);
            });
        }

        if (isset($data["filter_food_type"])) {
            $filter_food_type = $data["filter_food_type"];
            $listing_offers = $listing_offers->whereHas('listing', function ($query) use ($filter_food_type) {
                $query->whereHas('product', function ($query2) use ($filter_food_type) {
                    $query2->where('food_type_id', '=', $filter_food_type);
                });
            });
        }

        //$filter_donor_organization = $data["filter_donor_organization"];
        //$filter_product = $data["filter_product"];
        //$filter_food_type = $data["filter_food_type"];

        $listing_offers = $listing_offers->orderBy('listing_id')->get();

        $donor_organizations = Organization::where('status', 'active')
        ->where('type', 'donor')->get();

        $cso_organizations = Organization::where('status', 'active')
        ->where('type', 'cso')->get();

        $food_types = FoodType::where('status', 'active')->get();
        $products = Product::where('status', 'active')->get();

        $donor_organization_filter = '';
        $cso_organization_filter = '';
        $food_type_filter = '';
        $product_filter = '';

        if (isset($data['filter_donor_organization'])) {
            $donor_organization_filter = $data['filter_donor_organization'];
        }
        if (isset($data['filter_cso_organization'])) {
            $cso_organization_filter = $data['filter_cso_organization'];
        }
        if (isset($data['filter_food_type'])) {
            $food_type_filter = $data['filter_food_type'];
        }
        if (isset($data['filter_product'])) {
            $product_filter = $data['filter_product'];
        }

        return view('admin.donations_report')->with([
          'date_from' => $date_from,
          'date_to' => $date_to,
          'listing_offers' => $listing_offers,
          'donor_organizations' => $donor_organizations,
          'cso_organizations' => $cso_organizations,
          'food_types' => $food_types,
          'products' => $products,
          'data' => $data,
          'donor_organization_filter' => $donor_organization_filter,
          'cso_organization_filter' => $cso_organization_filter,
          'food_type_filter' => $food_type_filter,
          'product_filter' => $product_filter,

        ]);
    }
}
