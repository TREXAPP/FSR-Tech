<?php

namespace FSR\Http\Controllers\Admin;

use FSR;
use FSR\File;
use FSR\Listing;
use FSR\Location;
use FSR\Volunteer;
use FSR\ListingOffer;
use FSR\Organization;
use FSR\Custom\Methods;
use FSR\Http\Controllers\Controller;
use FSR\Notifications\Cso\NewVolunteer;
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
        $organizations = Organization::where('type', 'donor')
                               ->where('status', 'active');
        return view('admin.donor_organizations')->with([
          'organizations' => $organizations,
        ]);
    }
}
