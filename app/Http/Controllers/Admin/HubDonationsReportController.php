<?php

namespace FSR\Http\Controllers\Admin;

use FSR\Cso;
use FSR\Log;
use FSR\Hub;
use FSR\HubListing;
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

class HubDonationsReportController extends Controller
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
        //dd($data);
        $date_from = $data["filter_date_from"];
        $date_to = $data["filter_date_to"];
        $date_to_date = Carbon::parse($date_to)->addDays(1);
        $listing_offers = ListingOffer::where('offer_status', 'active')
                                    ->whereHas('hub_listing', function ($query) use ($date_from, $date_to_date) {
                                        $query->where('date_listed', '>=', $date_from)
                                            ->where('date_listed', '<=', $date_to_date);
                                    });

        $unaccepted_hub_listings = HubListing::where('status', 'active')
                                      ->where('date_listed', '>=', $date_from)
                                      ->where('date_listed', '<=', $date_to_date);

        $unaccepted_hub_listings = $unaccepted_hub_listings->whereDoesntHave('listing_offers', function ($query) {
            $query->where('offer_status', 'active');
        });

        if (isset($data["filter_cso_organization"])) {
            $filter_cso_organization = $data["filter_cso_organization"];
            $listing_offers = $listing_offers->whereHas('cso', function ($query) use ($filter_cso_organization) {
                $query->where('organization_id', '=', $filter_cso_organization);
            });
            $unaccepted_hub_listings = $unaccepted_hub_listings->where('id', '=', -1);
        }

        if (isset($data["filter_hub_organization"])) {
            $filter_hub_organization = $data["filter_hub_organization"];
            $listing_offers = $listing_offers->whereHas('hub_listing', function ($query) use ($filter_hub_organization) {
                $query->whereHas('hub', function ($query2) use ($filter_hub_organization) {
                    $query2->where('organization_id', '=', $filter_hub_organization);
                });
            });

            $unaccepted_hub_listings = $unaccepted_hub_listings->whereHas('hub', function ($query) use ($filter_hub_organization) {
                $query->where('organization_id', '=', $filter_hub_organization);
            });
        }

        if (isset($data["filter_product"])) {
            $filter_product = $data["filter_product"];
            $listing_offers = $listing_offers->whereHas('hub_listing', function ($query) use ($filter_product) {
                $query->where('product_id', '=', $filter_product);
            });
            $unaccepted_hub_listings = $unaccepted_hub_listings->where('product_id', '=', $filter_product);
        }

        if (isset($data["filter_food_type"])) {
            $filter_food_type = $data["filter_food_type"];
            $listing_offers = $listing_offers->whereHas('hub_listing', function ($query) use ($filter_food_type) {
                $query->whereHas('product', function ($query2) use ($filter_food_type) {
                    $query2->where('food_type_id', '=', $filter_food_type);
                });
            });
            $unaccepted_hub_listings = $unaccepted_hub_listings->whereHas('product', function ($query) use ($filter_food_type) {
                    $query->where('food_type_id', '=', $filter_food_type);
                });
        }

        $listing_offers = $listing_offers->orderBy('hub_listing_id')->get();
        $unaccepted_hub_listings = $unaccepted_hub_listings->orderBy('id')->get();

        $hub_organizations = Organization::where('status', 'active')
        ->where('type', 'hub')->get();

        $cso_organizations = Organization::where('status', 'active')
        ->where('type', 'cso')->get();

        $food_types = FoodType::where('status', 'active')->get();
        $products = Product::where('status', 'active')->get();

        $hub_organization_filter = '';
        $cso_organization_filter = '';
        $food_type_filter = '';
        $product_filter = '';

        if (isset($data['filter_hub_organization'])) {
            $hub_organization_filter = $data['filter_hub_organization'];
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

        return view('admin.hub_donations_report')->with([
          'date_from' => $date_from,
          'date_to' => $date_to,
          'listing_offers' => $listing_offers,
          'unaccepted_hub_listings' => $unaccepted_hub_listings,
          'hub_organizations' => $hub_organizations,
          'cso_organizations' => $cso_organizations,
          'food_types' => $food_types,
          'products' => $products,
          'data' => $data,
          'hub_organization_filter' => $hub_organization_filter,
          'cso_organization_filter' => $cso_organization_filter,
          'food_type_filter' => $food_type_filter,
          'product_filter' => $product_filter,

        ]);
    }
}
