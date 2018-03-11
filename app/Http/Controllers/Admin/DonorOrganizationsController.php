<?php

namespace FSR\Http\Controllers\Admin;

use FSR;
use FSR\File;
use FSR\Donor;
use FSR\Listing;
use FSR\Location;
use FSR\DonorType;
use FSR\Volunteer;
use FSR\ListingOffer;
use FSR\Organization;
use FSR\Custom\Methods;
use FSR\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

class DonorOrganizationsController extends Controller
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
      'donor_type' => '0'
    ];
        $donor_types = DonorType::where('status', 'active')->get();
        $organizations = Organization::where('type', 'donor')
                               ->where('status', 'active')->get();
        return $this->return_view($donor_types, $organizations, $filters);
    }

    /**
     * return view
     *
     * @param DonorType
     * @param Organization
     * @param Integer
     * @return \Illuminate\Http\Response
     */
    protected function return_view($donor_types, $organizations, $filters)
    {
        return view('admin.donor_organizations')->with([
        'filters' => $filters,
        'organizations' => $organizations,
        'donor_types' => $donor_types,
      ]);
    }


    public function handle_post(Request $request)
    {
        $data = $request->all();
        if (!empty($data['post-type'])) {
            switch ($data['post-type']) {
              case 'filter':
                if ($data['donor-types-filter-select']) {
                    return $this->handle_filter($data);
                } else {
                    return $this->index();
                }
                break;
                case 'delete':
                  return $this->handle_delete($request->all());
              break;

              default:
                return $this->index();
              break;
            }
        }
    }



    /**
     * Handle offer listing "delete". (it is actually update)
     *
     * @param  Array $data
     * @return \Illuminate\Http\Response
     */
    public function handle_delete(array $data)
    {
        $organization = $this->delete($data);
        return back()->with('status', "Организацијата е успешно избришана!");
    }

    /**
     * Mark the selected location as cancelled
     *
     * @param  array  $data
     * @return \FSR\FoodType
     */
    protected function delete(array $data)
    {
        $organization = Organization::find($data['donor_organization_id']);

        $donors = Donor::where('organization_id', $data['donor_organization_id'])
                        ->where('status', 'active')
                        ->update(['status' => 'deleted']);

        $organization->status = 'deleted';
        $organization->save();
        return $organization;
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
            'donor_type' => $data['donor-types-filter-select']
          ];
        $donor_types = DonorType::where('status', 'active')->get();
        $organizations = Organization::where('donor_type_id', $data['donor-types-filter-select'])
                               ->where('type', 'donor')
                               ->where('status', 'active')->get();
        return $this->return_view($donor_types, $organizations, $filters);
    }
}
