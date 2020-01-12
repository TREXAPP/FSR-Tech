<?php

namespace FSR\Http\Controllers\Admin;

use FSR\Cso;
use FSR\Log;
use FSR\Donor;
use FSR\Hub;
use FSR\Listing;
use FSR\ListingOffer;
use FSR\Organization;
use FSR\Custom\Methods;

use FSR\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegistrationReportController extends Controller
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

        $donors = Donor::where('created_at', '>=', $date_from)
                        ->where('created_at', '<=', $date_to_date)
                        ->orderBy('organization_id')->get();

        $csos = Cso::where('created_at', '>=', $date_from)
                        ->where('created_at', '<=', $date_to_date)
                        ->orderBy('organization_id')->get();

        $hubs = Hub::where('created_at', '>=', $date_from)
                        ->where('created_at', '<=', $date_to_date)
                        ->orderBy('organization_id')->get();

        $donor_organizations = Organization::where('type', 'donor')->orderBy('id')->get();

        $cso_organizations = Organization::where('type', 'cso')->get();

        $hub_organizations = Organization::where('type', 'hub')->get();

        $empty_donor_organizations_count = 0;
        foreach ($donor_organizations as $organization) {
            if ($organization->donors->count() == 0) {
                $empty_donor_organizations_count++;
            }
        }
        $empty_cso_organizations_count = 0;
        foreach ($cso_organizations as $organization) {
            if ($organization->csos->count() == 0) {
                $empty_cso_organizations_count++;
            }
        }
        $empty_hub_organizations_count = 0;
        foreach ($hub_organizations as $organization) {
            if ($organization->hubs->count() == 0) {
                $empty_hub_organizations_count++;
            }
        }


        $login_logs = Log::where('event', 'login')
                         ->where('created_at', '>=', $date_from)
                         ->where('created_at', '<=', $date_to_date);

        $home_page_logs = Log::where('event', 'open_home_page')
                             ->where('created_at', '>=', $date_from)
                             ->where('created_at', '<=', $date_to_date);

        return view('admin.registration_report')->with([
          'date_from' => $date_from,
          'date_to' => $date_to,
          'donor_organizations' => $donor_organizations,
          'cso_organizations' => $cso_organizations,
          'hub_organizations' => $hub_organizations,
          'login_logs' => $login_logs,
          'home_page_logs' => $home_page_logs,
          'donors' => $donors,
          'csos' => $csos,
          'hubs' => $hubs,
          'empty_donor_organizations_count' => $empty_donor_organizations_count,
          'empty_cso_organizations_count' => $empty_cso_organizations_count,
          'empty_hub_organizations_count' => $empty_hub_organizations_count,
        ]);
    }
}
