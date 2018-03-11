<?php

namespace FSR\Http\Controllers\Admin;

use FSR;
use FSR\File;
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

class DonorTypesController extends Controller
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
        $donor_types = DonorType::where('status', 'active')->get();
        return view('admin.donor_types')->with([
          'donor_types' => $donor_types,
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
        $donor_type = $this->delete($data);
        return back()->with('status', "Типот на донор е успешно избришан!");
    }

    /**
     * Mark the selected location as cancelled
     *
     * @param  array  $data
     * @return \FSR\DonorType
     */
    protected function delete(array $data)
    {
        $donor_type = DonorType::find($data['donor_type_id']);
        $donor_type->status = 'deleted';
        $donor_type->save();
        return $donor_type;
    }
}
