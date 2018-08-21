<?php

namespace FSR\Http\Controllers\Auth;

use FSR\User;
use FSR\Cso;
use FSR\Admin;
use FSR\Donor;
use FSR\Location;
use FSR\Timeframe;
use FSR\Volunteer;
use FSR\Organization;
use FSR\File;
use FSR\TransportType;
use FSR\VolunteersLocation;
use FSR\VolunteerAvailability;
use FSR\VolunteersOrganization;
use FSR\VolunteersTransportType;
use FSR\Custom\Methods;
use FSR\Notifications\UserToAdminsRegister;
use FSR\Notifications\FreeVolunteerToAdminsRegister;
use FSR\Notifications\FreeVolunteerApplicationSuccess;


use FSR\Http\Controllers\Controller;
use FSR\Notifications\UserRegistrationSuccess;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;

class FreeVolunteersController extends Controller
{

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locations = Location::where('status', 'active')->get();
        $organizations = Organization::where('status', 'active')
                                      ->where('type', 'cso')->get();
        $transport_types = TransportType::where('status', 'active')->get();
        $timeframes = Timeframe::where('status', 'active')->orderby('hours_from', 'ASC');
        $timeframes_rows = Timeframe::where('status', 'active')->select('day')->distinct()->get();
        $timeframes_cols = Timeframe::where('status', 'active')->orderby('hours_from', 'ASC')->select(['hours_from', 'hours_to'])->distinct()->get();

        return view('auth.free_volunteers')->with([
          // 'organizations' => $organizations,
          'locations' => $locations,
          'organizations' => $organizations,
          'transport_types' => $transport_types,
          'timeframes' => $timeframes,
          'timeframes_cols' => $timeframes_cols,
          'timeframes_rows' => $timeframes_rows,
        ]);
    }

    public function handle_post(Request $request)
    {
        $data = $request->all();
        $validation = $this->validator($data);
        //$this->validator($request->all())->validate();
        if ($validation->fails()) {
            return back()->withErrors($validation->errors())
                                                 ->withInput();
        }

        //$file_id = $this->new_product_handle_upload($request);
        $free_volunteer = $this->create($data);
        $free_volunteer->notify(new FreeVolunteerApplicationSuccess());
        $admins = Admin::where('email', '!=', 'sitesitimk@gmail.com')
                  ->where('status', 'active')->get();
        Notification::send($admins, new FreeVolunteerToAdminsRegister($free_volunteer));
        //return back()->with('status', "Вашата апликација е успешна! Ќе бидете контактирани по е-маил.");
        return redirect(route('login'))->with('status', "Вашата апликација е успешна! Ќе бидете контактирани по е-маил.");
    }

    protected function validator(array $data)
    {
        $validatorArray = [
            'first_name'                    => 'required',
            'last_name'                     => 'required',
            'email'                         => 'required|string|email|max:255|unique:donors|unique:csos|unique:volunteers',
            'address'                       => 'required',
            'phone'                         => 'required',
            'locations'                     => 'required',
            'free_volunteer_type'           => 'required',
        ];
        if ($data['free_volunteer_type'] == 'transport_food') {
            $validatorArray = array_merge(
              $validatorArray,
              array('transport_types' => 'required')
          );
        }
        return Validator::make($data, $validatorArray);
    }

    protected function create(array $data)
    {
        $volunteer = Volunteer::create([
        'first_name' => $data['first_name'],
        'last_name' => $data['last_name'],
        'email' => $data['email'],
        'phone' => $data['phone'],
        'status' => 'pending',
        'is_user' => 0,
        'type' => $data['free_volunteer_type'],
        'address' => $data['address'],
      ]);

        foreach ($data['locations'] as $location) {
            $volunteer_location = VolunteersLocation::create([
          'volunteer_id' => $volunteer->id,
          'location_id' => $location,
          'status' => 'active'
        ]);
        }

        if ($data['free_volunteer_type'] == 'transport_food') {
            //transport types
            foreach ($data['transport_types'] as $transport_type) {
                $volunteer_transport_type = VolunteersTransportType::create([
              'volunteer_id' => $volunteer->id,
              'transport_type_id' => $transport_type,
              'status' => 'active'
            ]);
            }
            //organizations
            foreach ($data['organizations'] as $organization) {
                $volunteer_organization = VolunteersOrganization::create([
              'volunteer_id' => $volunteer->id,
              'organization_id' => $organization,
              'type' => 'volunteer_preference',
              'status' => 'active'
              ]);
            }
            //availability
            $indices_array = array_keys($data);
            foreach ($indices_array as $index) {
                if (substr($index, 0, 17) === "chk_availability_") {
                    $timeframe_id = substr($index, 17);
                    $volunteer_availability = VolunteerAvailability::create([
                  'volunteer_id' => $volunteer->id,
                  'timeframe_id' => $timeframe_id,
                  'status' => 'active'
                ]);
                }
            }
        }
        // return  Product::create([
        //         'name' => $data['name'],
        //         'description' => $data['description'],
        //         'food_type_id' => $data['food_type'],
        //     ]);

        return $volunteer;
    }
}
