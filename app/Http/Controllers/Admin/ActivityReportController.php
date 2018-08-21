<?php

namespace FSR\Http\Controllers\Admin;

use FSR\Cso;
use FSR\Log;
use FSR\Donor;
use FSR\Listing;
use FSR\ListingOffer;
use FSR\Organization;
use FSR\Custom\Methods;

use FSR\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ActivityReportController extends Controller
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

        $donors = Donor::where('status', 'active')->orderBy('organization_id')->get();
        $csos = Cso::where('status', 'active')->orderBy('organization_id')->get();

        $donor_organizations = Organization::where('status', 'active')
                                           ->where('type', 'donor')->orderBy('id')->get();

        $cso_organizations = Organization::where('status', 'active')
                                           ->where('type', 'cso')->get();

        $empty_donor_organizations_count = 0;
        foreach ($donor_organizations as $organization) {
            if ($organization->donors->where('status', 'active')->count() == 0) {
                $empty_donor_organizations_count++;
            }
        }
        $empty_cso_organizations_count = 0;
        foreach ($cso_organizations as $organization) {
            if ($organization->csos->where('status', 'active')->count() == 0) {
                $empty_cso_organizations_count++;
            }
        }


        $login_logs = Log::where('event', 'login')
                         ->where('created_at', '>=', $date_from)
                         ->where('created_at', '<=', $date_to);

        $home_page_logs = Log::where('event', 'open_home_page')
                             ->where('created_at', '>=', $date_from)
                             ->where('created_at', '<=', $date_to);


        // $listings = Listing::where('date_expires', $listing_status_operator, Carbon::now()->format('Y-m-d H:i'))
        //                         ->where('date_listed', '>=', $date_from)
        //                         ->where('date_listed', '<=', $date_to)
        //                         ->where('listing_status', 'active')
        //                         ->withCount('listing_offers')
        //                         ->withCount(['listing_offers' => function ($query) {
        //                             $query->where('offer_status', 'active');
        //                         }])
        //                         ->orderBy('date_expires', 'ASC');

        return view('admin.activity_report')->with([
          'date_from' => $date_from,
          'date_to' => $date_to,
          'donor_organizations' => $donor_organizations,
          'cso_organizations' => $cso_organizations,
          'login_logs' => $login_logs,
          'home_page_logs' => $home_page_logs,
          'donors' => $donors,
          'csos' => $csos,
          'empty_donor_organizations_count' => $empty_donor_organizations_count,
          'empty_cso_organizations_count' => $empty_cso_organizations_count,
        ]);
    }
}
