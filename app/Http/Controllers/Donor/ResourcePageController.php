<?php

namespace FSR\Http\Controllers\Donor;

use FSR;
use FSR\File;
use FSR\Listing;
use FSR\Location;
use FSR\FoodType;
use FSR\Resource;
use FSR\ListingOffer;
use FSR\Custom\Methods;

use FSR\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

class ResourcePageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:donor');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $resource = Resource::where('name', 'resource_page_donor')->first();
        return view('donor.resource_page')->with([
              'resource' => $resource,
          ]);
    }
}
