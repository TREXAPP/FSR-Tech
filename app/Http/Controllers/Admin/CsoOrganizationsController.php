<?php

namespace FSR\Http\Controllers\Admin;

use FSR;
use FSR\Cso;
use FSR\File;
use FSR\Listing;
use FSR\Location;
use FSR\Volunteer;
use FSR\ListingOffer;
use FSR\Organization;
use FSR\Custom\Methods;
use FSR\Notifications\AdminToVolunteerRemoved;

use FSR\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;

class CsoOrganizationsController extends Controller
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
        $organizations = Organization::where('type', 'cso')
                               ->where('status', 'active');
        return view('admin.cso_organizations')->with([
          'organizations' => $organizations,
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
        $organization = $this->delete($data);
        return back()->with('status', "Организацијата е успешно избришана!");
    }

    /**
     * Mark the selected location as cancelled
     *
     * @param  array  $data
     * @return \FSR\FoodType
     */
    protected function delete(array $data)
    {
        $organization = Organization::find($data['cso_organization_id']);

        $csos = Cso::where('organization_id', $data['cso_organization_id'])
                    ->where('status', 'active')
                    ->update(['status' => 'deleted']);
        $volunteers = Volunteer::where('organization_id', $data['cso_organization_id'])
                                ->where('is_user', '0');
        $volunteers->update(['status' => 'deleted']);
        Notification::send($volunteers->get(), new AdminToVolunteerRemoved($organization));

        $organization->status = 'deleted';
        $organization->save();
        return $organization;
    }
}
