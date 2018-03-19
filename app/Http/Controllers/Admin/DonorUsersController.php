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
        $donor = $this->delete($data);
        return back()->with('status', "Корисникот е успешно избришан!");
    }

    /**
     * Mark the selected location as cancelled
     *
     * @param  array  $data
     * @return \FSR\Donor
     */
    protected function delete(array $data)
    {
        // - delete the donor
        // - listings from that donor
        // - listing offers for the listings from this donor


        $donor = Donor::find($data['donor_id']);
        $listings = $donor->listings->where('listing_status', 'active')
                                    ->where('date_expires', '>', Carbon::now()->format('Y-m-d H:i'))
                                    ->where('date_listed', '<', Carbon::now()->format('Y-m-d H:i'));

        //delete listing_offers
        foreach ($listings as $listing) {
            $listing_offers = $listing->listing_offers->where('offer_status', 'active');
            foreach ($listing_offers as $listing_offer) {
                $listing_offer->offer_status = 'deleted';
                $listing_offer->save();
            }
        }

        //delete listings
        foreach ($listings as $listing) {
            $listing->listing_status = 'deleted';
            $listing->save();
        }

        //delete donor
        $donor->status = 'deleted';
        $donor->save();
        return $donor;
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
