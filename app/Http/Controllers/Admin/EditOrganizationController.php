<?php

namespace FSR\Http\Controllers\Admin;

use FSR;
use FSR\File;
use FSR\Listing;
use FSR\Location;
use FSR\DonorType;
use FSR\Region;
use FSR\Organization;
use FSR\ListingOffer;
use FSR\Custom\Methods;
use FSR\Notifications\CsoToOrganizationRemoved;

use FSR\Http\Controllers\Controller;
use FSR\Notifications\CsoToOrganizationNewOrganization;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

class EditOrganizationController extends Controller
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
    public function index(Request $request, string $organization_id_string)
    {
        $donor_types = DonorType::where('status', 'active')->get();
        $regions = Region::where('status', 'active')->get();
        $organization_id = ctype_digit($organization_id_string) ? intval($organization_id_string) : null;
        if ($organization_id === null) {
            return redirect(route('admin.organizations'));
        } else {
            $organization = Organization::find($organization_id);
            if (!$organization) {
                return redirect(route('admin.organizations'));
            } else {
                return view('admin.edit_organization')->with([
              'organization' => $organization,
              'donor_types' => $donor_types,
              'regions' => $regions,
          ]);
            }
        }
    }


    /**
     * set information for image upload for editing an existing organization
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function edit_handle_upload(Request $request)
    {
        $input_name = 'organization-image';
        $purpose = 'organization image';
        $for_user_type = 'admin';
        $description = 'A organization image uploaded when editing an existing organization via organizations/{{id}}';

        return Methods::handleUpload($request, $input_name, $purpose, $for_user_type, $description);
    }

    /**
     * Get a validator for a new organization insert
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        switch ($data['organization-type']) {
        case 'cso':
        $validatorArray = [
          'organization-name'          => 'required',
          'organization-address'       => 'required',
          'organization-image'         => 'image|max:2048',
                ];
          break;

        case 'donor':
        $validatorArray = [
          'organization-donor-type'    => 'required',
          'organization-name'          => 'required',
          'organization-address'       => 'required',
          'organization-image'         => 'image|max:2048',
          'working_hours_from'         => 'required',
          'working_hours_to'           => 'required',
                ];
          break;

        case 'hub':
        $validatorArray = [
          'organization-region'        => 'required',
          'organization-name'          => 'required',
          'organization-address'       => 'required',
          'organization-image'         => 'image|max:2048',
          'working_hours_from'         => 'required',
          'working_hours_to'           => 'required',
                ];
          break;
        default:
        //
          break;
      }


        return Validator::make($data, $validatorArray);
    }


    /**
     * Handle "edit organization". - post
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle_post(Request $request)
    {
        $organization_id = $request->all()['organization_id'];
        $organization = Organization::find($organization_id);

        if ($this->change_detected($request, $organization)) {
            $validation = $this->validator($request->all());

            if ($validation->fails()) {
                return back()->withErrors($validation->errors())
                                                 ->withInput();
            }

            $file_id = $this->edit_handle_upload($request);
            $organization = $this->update($organization, $request->all(), $file_id);
            return redirect(route('admin.' . $organization->type . '_organizations'))->with('status', "Измените се успешно зачувани!");
        } else {
            return back();
        }
    }

    /**
     * Indicates if changes are being made to the organization information
     *
     * @param  Request  $request
     * @param  Organization $organization
     * @return bool
     */
    protected function change_detected(Request $request, Organization $organization)
    {
        $data = $request->all();

        if ($request->hasFile('organization-image')) {
            return true;
        }
        if ($organization->name != $data['organization-name']) {
            return true;
        }
        if ($organization->address != $data['organization-address']) {
            return true;
        }
        if ($organization->description != $data['organization-description']) {
            return true;
        }

        if ($organization->type == 'donor') {
            if ($organization->donor_type != $data['organization-donor-type']) {
                return true;
            }
            if ($organization->working_hours_from != $data['working_hours_from']) {
                return true;
            }
            if ($organization->working_hours_to != $data['working_hours_to']) {
                return true;
            }
        }

        if ($organization->type == 'hub') {
            if ($organization->region_id != $data['organization-region']) {
                return true;
            }
            if ($organization->working_hours_from != $data['working_hours_from']) {
                return true;
            }
            if ($organization->working_hours_to != $data['working_hours_to']) {
                return true;
            }
        }


        return false;
    }

    /**
     * updates the information for the profile
     *
     * @param  Organization $organization
     * @param  array  $data
     * @param  int  $file_id
     * @return FSR\Organization $organization
     */
    protected function update(Organization $organization, array $data, $file_id)
    {
        if ($file_id) {
            $organization->image_id = $file_id;
        }
        $organization->name = $data['organization-name'];
        $organization->address = $data['organization-address'];
        $organization->description = $data['organization-description'];

        if ($organization->type == 'donor') {
            $organization->donor_type_id = $data['organization-donor-type'];
            $organization->working_hours_from = $data['working_hours_from'];
            $organization->working_hours_to = $data['working_hours_to'];
        }

        if ($organization->type == 'hub') {
            $organization->region_id = $data['organization-region'];
            $organization->working_hours_from = $data['working_hours_from'];
            $organization->working_hours_to = $data['working_hours_to'];
        }
        $organization->save();

        return $organization;
    }
}
