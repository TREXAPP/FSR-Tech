<?php

namespace FSR\Http\Controllers\Admin;

use FSR;
use FSR\File;
use FSR\Cso;
use FSR\Hub;
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

class HubUsersController extends Controller
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
        $filters = [
        'organization' => '0'
      ];
        $organizations = Organization::where('status', 'active')
                                     ->where('type', 'hub')->get();
        $hubs = Hub::where('status', 'active')->get();
        return $this->return_view($hubs, $organizations, $filters);
    }

    /**
     * return view
     *
     * @param FoodType
     * @param Product
     * @param Integer
     * @return \Illuminate\Http\Response
     */
    protected function return_view($hubs, $organizations, $filters)
    {
        return view('admin.hub_users')->with([
        'filters' => $filters,
        'hubs' => $hubs,
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
        $hub = $this->delete($data);
        return back()->with('status', "Хабот е успешно избришан!");
    }

    /**
     * Mark the selected location as cancelled
     *
     * @param  array  $data
     * @return \FSR\FoodType
     */
    protected function delete(array $data)
    {

      //delete listing_offers from this cso
        //then delete cso
        $hub = Hub::find($data['hub_id']);

        //TODO: Delete hub listing offers here
        // $listing_offers = ListingOffer::where('cso_id', $cso->id)
        //                               ->where('offer_status', 'active')
        //                               ->whereHas('listing', function ($query) {
        //                                   $query->where('date_expires', '>', Carbon::now()->format('Y-m-d H:i'))
        //                                     ->where('date_listed', '<', Carbon::now()->format('Y-m-d H:i'));
        //                               })->get();

        //dd($listing_offers);
        // foreach ($listing_offers as $listing_offer) {
        //     $listing_offer->offer_status = 'deleted';
        //     $listing_offer->save();
        // }

        //delete the hub
        $hub->status = 'deleted';
        $hub->save();
        return $hub;
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
                                       ->where('type', 'hub')->get();
        $hubs = Hub::where('status', 'active')
                               ->where('organization_id', $data['organizations-filter-select'])->get();
        return $this->return_view($hubs, $organizations, $filters);
    }
}
