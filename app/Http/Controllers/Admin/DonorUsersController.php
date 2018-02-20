<?php

namespace FSR\Http\Controllers\Admin;

use FSR;
use FSR\File;
use FSR\Donor;
use FSR\Listing;
use FSR\FoodType;
use FSR\Location;
use FSR\ListingOffer;
use FSR\Organization;
use FSR\QuantityType;
use FSR\Custom\Methods;
use FSR\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

class DonorUsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $filters = [
        'organization' => '0'
      ];
        $organizations = Organization::where('status', 'active')
                                     ->where('type', 'donor')->get();
        $donors = donor::where('status', 'active')->get();
        return $this->return_view($donors, $organizations, $filters);
    }

    /**
     * return view
     *
     * @param FoodType
     * @param Product
     * @param Integer
     * @return \Illuminate\Http\Response
     */
    protected function return_view($donors, $organizations, $filters)
    {
        return view('admin.donor_users')->with([
        'filters' => $filters,
        'donors' => $donors,
        'organizations' => $organizations,
      ]);
    }

    public function handle_post(Request $request)
    {
        $data = $request->all();
        if (!empty($data['post-type'])) {
            switch ($data['post-type']) {
          case 'filter':
            if ($data['organizations-filter-select']) {
                return $this->handle_filter($data);
            } else {
                return $this->index();
            }

          break;

          default:
            return $this->index();
          break;
        }
        }
    }


    /**
     * handle post from filter
     *
     * @param Array $data
     * @return \Illuminate\Http\Response
     */
    protected function handle_filter(array $data)
    {
        $filters = [
            'organization' => $data['organizations-filter-select']
          ];
        $organizations = Organization::where('status', 'active')
                                       ->where('type', 'donor')->get();
        $donors = Donor::where('status', 'active')
                               ->where('organization_id', $data['organizations-filter-select'])->get();
        return $this->return_view($donors, $organizations, $filters);
    }
}
