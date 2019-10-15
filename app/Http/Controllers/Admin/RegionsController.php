<?php

namespace FSR\Http\Controllers\Admin;

use FSR;
use FSR\File;
use FSR\Listing;
use FSR\FoodType;
use FSR\Region;
use FSR\Location;
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

class RegionsController extends Controller
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
        $regions = Region::where('status', 'active')->get();
        return view('admin.regions')->with([
          'regions' => $regions,
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
        $region = $this->delete($data);
        return back()->with('status', "Регионот е успешно избришана!");
    }

    /**
     * Mark the selected region as cancelled
     *
     * @param  array  $data
     * @return \FSR\Region
     */
    protected function delete(array $data)
    {
        $region = Region::find($data['region_id']);
        $region->status = 'deleted';
        $region->save();
        return $region;
    }
}
