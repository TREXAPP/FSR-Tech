<?php

namespace FSR\Http\Controllers\Admin;

use FSR;
use FSR\Hub;
use FSR\File;
use FSR\Listing;
use FSR\Location;
use FSR\Volunteer;
use FSR\ListingOffer;
use FSR\Organization;
use FSR\Custom\Methods;
use FSR\Notifications\AdminToVolunteerRemoved;

use FSR\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;

class HubOrganizationsController extends Controller
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
        $organizations = Organization::where('type', 'hub')
                               ->where('status', 'active');
        return view('admin.hub_organizations')->with([
          'organizations' => $organizations,
        ]);
    }


    public function handle_post(Request $request)
    {
        $data = $request->all();
        if (!empty($data['post-type'])) {
            switch ($data['post-type']) {
                      case 'delete':
                        return $this->handle_delete($request->all());
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
        $organization = Organization::find($data['hub_organization_id']);
        //$csos = $organization->csos;
        $hubs = Hub::where('organization_id', $data['hub_organization_id'])
                    ->where('status', 'active')->get();
        //dd($csos->get());
        foreach ($hubs as $hub) {
            $listing_offers = ListingOffer::where('hub_id', $hub->id)
                                          ->where('offer_status', 'active')
                                          ->whereHas('listing', function ($query) {
                                              $query->where('date_expires', '>', Carbon::now()->format('Y-m-d H:i'))
                                                ->where('date_listed', '<', Carbon::now()->format('Y-m-d H:i'));
                                          })->get();

            //TODO: Delete all connected entries as well

            $hub->status = 'deleted';
            $hub->save();
        }

        $organization->status = 'deleted';
        $organization->save();
        return $organization;
    }
}
