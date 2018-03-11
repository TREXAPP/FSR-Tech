<?php

namespace FSR\Http\Controllers\Admin;

use FSR;
use FSR\File;
use FSR\Listing;
use FSR\FoodType;
use FSR\Location;
use FSR\Volunteer;
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

class QuantityTypesController extends Controller
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
        $quantity_types = QuantityType::where('status', 'active')->get();
        return view('admin.quantity_types')->with([
          'quantity_types' => $quantity_types,
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
        $quantity_type = $this->delete($data);
        return back()->with('status', "Количината е успешно избришана!");
    }

    /**
     * Mark the selected location as cancelled
     *
     * @param  array  $data
     * @return \FSR\DonorType
     */
    protected function delete(array $data)
    {
        $quantity_type = QuantityType::find($data['quantity_type_id']);
        $quantity_type->status = 'deleted';
        $quantity_type->save();
        return $quantity_type;
    }
}
