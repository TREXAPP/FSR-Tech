<?php

namespace FSR\Http\Controllers\Admin;

use FSR;
use FSR\File;
use FSR\Cso;
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

class CsoUsersController extends Controller
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
                                     ->where('type', 'cso')->get();
        $csos = Cso::where('status', 'active')->get();
        return $this->return_view($csos, $organizations, $filters);
    }

    /**
     * return view
     *
     * @param FoodType
     * @param Product
     * @param Integer
     * @return \Illuminate\Http\Response
     */
    protected function return_view($csos, $organizations, $filters)
    {
        return view('admin.cso_users')->with([
        'filters' => $filters,
        'csos' => $csos,
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
        $cso = $this->delete($data);
        return back()->with('status', "Корисникот е успешно избришан!");
    }

    /**
     * Mark the selected location as cancelled
     *
     * @param  array  $data
     * @return \FSR\FoodType
     */
    protected function delete(array $data)
    {
        $cso = Cso::find($data['cso_id']);
        $cso->status = 'deleted';
        $cso->save();
        return $cso;
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
                                       ->where('type', 'cso')->get();
        $csos = Cso::where('status', 'active')
                               ->where('organization_id', $data['organizations-filter-select'])->get();
        return $this->return_view($csos, $organizations, $filters);
    }
}
