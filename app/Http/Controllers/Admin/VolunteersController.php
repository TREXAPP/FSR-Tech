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
use FSR\Notifications\AdminToVolunteerRemoved;

use FSR\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

class VolunteersController extends Controller
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
        $filters = [
        'organization' => '0'
      ];
        $organizations = Organization::where('status', 'active')
                                     ->where('type', 'cso')->get();
        $volunteers = Volunteer::where('is_user', '0')
                                 ->where('status', 'active')->get();
        return $this->return_view($volunteers, $organizations, $filters);
    }

    /**
     * return view
     *
     * @param FoodType
     * @param Product
     * @param Integer
     * @return \Illuminate\Http\Response
     */
    protected function return_view($volunteers, $organizations, $filters)
    {
        return view('admin.volunteers')->with([
        'filters' => $filters,
        'volunteers' => $volunteers,
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
        $volunteer = $this->delete($data);
        $volunteer->notify(new AdminToVolunteerRemoved($volunteer->organization));
        return back()->with('status', "Подигнувачот е успешно избришан!");
    }

    /**
     * Mark the selected listing offer as cancelled
     *
     * @param  array  $data
     * @return \FSR\Volunteer
     */
    protected function delete(array $data)
    {
        $volunteer = Volunteer::find($data['volunteer_id']);
        $volunteer->status = 'deleted';
        $volunteer->save();
        return $volunteer;
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
        $volunteers = Volunteer::where('is_user', '0')
                               ->where('organization_id', $data['organizations-filter-select'])->get();
        return $this->return_view($volunteers, $organizations, $filters);
    }
}
