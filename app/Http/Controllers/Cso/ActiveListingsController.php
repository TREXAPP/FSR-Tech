<?php

namespace FSR\Http\Controllers\Cso;

use FSR\Listing;
use FSR\File;
use FSR\Volunteer;
use FSR\ListingOffer;
use FSR\Http\Controllers\Controller;
use FSR\Notifications\Cso\AcceptListing;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use FSR\Custom\Methods;

class ActiveListingsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:cso');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //  $active_listings = Listing::where('listing_status', 'active');
        $active_listings = Listing::where('date_expires', '>', Carbon::now()->format('Y-m-d H:i'))
            ->where('date_listed', '<=', Carbon::now()->format('Y-m-d H:i'))
            ->where('listing_status', 'active')
            ->orderBy('date_expires', 'ASC');

        $listing_offers = ListingOffer::where('offer_status', 'active')->get();
        //$listing_offers = ListingOffer::all();
        $active_listings_no = 0;
        foreach ($active_listings->get() as $active_listing) {
            $quantity_counter = 0;
            foreach ($active_listing->listing_offers as $listing_offer) {
                if ($listing_offer->offer_status == 'active') {
                    $quantity_counter += $listing_offer->quantity;
                }
            }
            if ($active_listing->quantity > $quantity_counter) {
                $active_listings_no++;
            }
        }

        $volunteers = Volunteer::where('organization_id', Auth::user()->organization_id)
                               ->where('status', 'active')->get();

        return view('cso.active_listings')->with([
            'active_listings' => $active_listings,
            'listing_offers' => $listing_offers,
            'active_listings_no' => $active_listings_no,
            'volunteers' => $volunteers,
        ]);
    }

    /**
     * Handle post request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle_post(Request $request)
    {
        $validation = $this->validator($request->all());

        //  http://fsr.test/cso/active_listings#listingbox6
        $route = route('cso.active_listings') . '#listingbox' . $request->all()['listing_id'];

        if ($validation->fails()) {
            return redirect($route)->withErrors($validation->errors())
                ->withInput();
        }

        // $csos = Cso::where('location_id', Auth::user()->location_id)
        //            ->where('notifications', 1)->get();
        //
        // Notification::send($csos, new NewListing($listing));

        $listing_offer = $this->create($request->all());

        $listing_offer->listing->donor->notify(new AcceptListing($listing_offer));
        return back()->with('status', "Донацијата е успешно прифатена!");
    }

    /**
     * Create a new listing_offer instance after a valid input.
     *
     * @param  array  $data
     * @return \FSR\ListingOffer
     */
    protected function create(array $data)
    {
        return ListingOffer::create([
            'cso_id' => Auth::user()->id,
            'listing_id' => $data['listing_id'],
            'offer_status' => 'active',
            'quantity' => $data['quantity'],
            'volunteer_id' => $data['volunteer'],
        ]);
    }

    /**
     * Get a validator for an incoming listing offer input request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $listing = Listing::find($data['listing_id']);
        $listing_offers = $listing->listing_offers;
        $quantity_counter = 0;
        foreach ($listing_offers as $listing_offer) {
            if ($listing_offer->offer_status == 'active') {
                $quantity_counter += $listing_offer->quantity;
            }
        }
        $max_quantity = $listing->quantity - $quantity_counter;

        $validatorArray = [
            'listing_id' => 'required',
            'quantity' => 'required|numeric|min:1|max:' . $max_quantity,
            'volunteer' => 'required',
        ];

        return Validator::make($data, $validatorArray);
    }

    /**
     * Handle post request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add_volunteer(Request $request)
    {
        $validation = $this->validator_volunteer($request->all());

        if ($validation->fails()) {
            //  return $validation->errors();
            return response()->json(['errors' => $validation->errors()]);
        }

        $file_id = $this->handle_upload_ajax($request);
        $volunteer = $this->create_volunteer($request->all(), $file_id);

        //  Notification::send($csos, new NewListing($listing));
        return response()->json(['id' => $volunteer->id]);
    }

    /**
     * set information for image upload for adding new volunteer through ajax
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function handle_upload_ajax(Request $request)
    {
        $input_name = 'image';
        $purpose = 'volunteer image';
        $for_user_type = 'cso';
        $description = 'An uploaded image for added volunteer with ajax through popup in active listings.';

        return Methods::handleUpload($request, $input_name, $purpose, $for_user_type, $description);
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \FSR\User
     */
    protected function create_volunteer(array $data, $file_id)
    {
        return Volunteer::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'image_id' => $file_id,
            'organization_id' => Auth::user()->organization_id,
            'added_by_user_id' => Auth::user()->id,
        ]);
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator_volunteer(array $data)
    {
        $validatorArray = [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'email' => 'required|string|email|max:255',
            'image' => 'image|max:2048',
        ];

        return Validator::make($data, $validatorArray);
    }

    /**
     * Handle post request. Get volunteers with ajax
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function get_volunteers(Request $request)
    {
        return Volunteer::where('organization_id', Auth::user()->organization_id)
                        ->where('status', 'active')->get();
    }
}
