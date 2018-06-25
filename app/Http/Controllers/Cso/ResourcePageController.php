<?php

namespace FSR\Http\Controllers\Cso;

use FSR;
use FSR\File;
use FSR\Listing;
use FSR\Location;
use FSR\FoodType;
use FSR\Resource;
use FSR\ListingOffer;
use FSR\Custom\Methods;
use FSR\Notifications\CsoToFoodTypeRemoved;

use FSR\Http\Controllers\Controller;
use FSR\Notifications\CsoToFoodTypeNewFoodType;
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
        $this->middleware('auth:cso');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $resource = Resource::where('name', 'resource_page_cso')->first();
        return view('cso.resource_page')->with([
              'resource' => $resource,
          ]);
    }
}
