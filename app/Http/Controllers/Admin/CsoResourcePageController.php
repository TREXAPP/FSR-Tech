<?php

namespace FSR\Http\Controllers\Admin;

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

class CsoResourcePageController extends Controller
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
    public function index(Request $request)
    {
        $resource = Resource::where('name', 'resource_page_cso')->first();
        return view('admin.edit_resource_page_cso')->with([
              'resource' => $resource,
          ]);
    }

    /**
     * Handle post
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle_post(Request $request)
    {
        $description = $request->all()['resource-page-description'];
        $resource = Resource::where('name', 'resource_page_cso')->first();
        $resource = $this->update($resource, $description);
        return redirect(route('admin.resource_page_cso'))->with('status', "Измените се успешно зачувани!");
    }


    /**
     * updates the information
     *
     * @param  Resource $resource
     * @param  string  $description
     * @return FSR\Resource $resource
     */
    protected function update(Resource $resource, string $description)
    {
        $resource->description = $description;
        $resource->save();
        return $resource;
    }
}
