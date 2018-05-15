<?php

namespace FSR\Http\Controllers\Cso;

use FSR;
use FSR\File;
use FSR\Listing;
use FSR\Location;
use FSR\Volunteer;
use FSR\ListingOffer;
use FSR\Custom\Methods;
use FSR\Notifications\CsoToVolunteerRemoved;

use FSR\Http\Controllers\Controller;
use FSR\Notifications\CsoToVolunteerNewVolunteer;
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
        $this->middleware('auth:cso');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $volunteers = Volunteer::where('organization_id', Auth::user()->organization_id)
                               ->where('status', 'active');
        return view('cso.volunteers')->with([
          'volunteers' => $volunteers,
        ]);
    }


    /**
     * Handle post request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle_post(Request $request)
    {
        // if ($request->has('edit-volunteer-popup')) {
        //     return $this->handle_update_volunteer($request);
        // } elseif ($request->has('delete-volunteer-popup')) {
        return $this->handle_delete($request);
        // }
    }



    /**
     * Handle offer listing "delete". (it is actually update)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle_delete(Request $request)
    {
        $volunteer = $this->delete($request->all());
        $volunteer->notify(new CsoToVolunteerRemoved($volunteer->organization));
        return back()->with('status', "Доставувачот е успешно избришан!");
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
     * Open "new volunteer" form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function new_volunteer(Request $request)
    {
        return view('cso.new_volunteer');
    }

    /**
     * Handle "new volunteer" post.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function new_volunteer_post(Request $request)
    {
        $cso = Auth::user();
        $validation = $this->volunteer_validator($request->all());

        if ($validation->fails()) {
            return back()->withErrors($validation->errors())
                                             ->withInput();
        }

        $file_id = $this->new_handle_upload($request);
        $volunteer = $this->create_volunteer($request->all(), $file_id);

        $volunteer->notify(new CsoToVolunteerNewVolunteer($volunteer, $cso));

        return redirect(route('cso.volunteers'))->with('status', "Доставувачот е внесен успешно!");
    }

    /**
     * set information for image upload for adding new volunteer
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function new_handle_upload(Request $request)
    {
        $input_name = 'volunteer-image';
        $purpose = 'volunteer image';
        $for_user_type = 'cso';
        $description = 'A new volunteer image uploaded when creating a new volunteer via volunteers/new';

        return Methods::handleUpload($request, $input_name, $purpose, $for_user_type, $description);
    }

    /**
     * set information for image upload for editing an existing volunteer
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function edit_handle_upload(Request $request)
    {
        $input_name = 'volunteer-image';
        $purpose = 'volunteer image';
        $for_user_type = 'cso';
        $description = 'A volunteer image uploaded when editing an existing volunteer via volunteers/{{id}}';

        return Methods::handleUpload($request, $input_name, $purpose, $for_user_type, $description);
    }

    /**
     * Get a validator for a new volunteer insert
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function volunteer_validator(array $data)
    {
        $validatorArray = [
                'volunteer-first-name'    => 'required',
                'volunteer-last-name'     => 'required',
                'volunteer-image'         => 'image|max:2048',
                'volunteer-email'         => 'required|string|email|max:255|unique:donors,email|unique:csos,email|unique:volunteers,email',
                'volunteer-phone'         => 'required',
            ];

        return Validator::make($data, $validatorArray);
    }

    /**
     * Get a validator for update volunteer (when the email is not changed)
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function volunteer_validator_update(array $data)
    {
        $validatorArray = [
                'volunteer-first-name'    => 'required',
                'volunteer-last-name'     => 'required',
                'volunteer-image'         => 'image|max:2048',
                'volunteer-phone'         => 'required',
            ];

        return Validator::make($data, $validatorArray);
    }


    /**
     * inserts a new volunteer to the model
     *
     * @param  array  $data
     * @param  int  $file_id
     * @return FSR\Volunteer $volunteer
     */
    protected function create_volunteer($data, $file_id)
    {
        return Volunteer::create([
          'first_name' => $data['volunteer-first-name'],
          'last_name' => $data['volunteer-last-name'],
          'email' => $data['volunteer-email'],
          'phone' => $data['volunteer-phone'],
          'image_id' => $file_id,
          'organization_id' => Auth::user()->organization_id,
          'added_by_user_id' => Auth::user()->id,
      ]);
    }

    /**
     * Handle "edit volunteer". - get
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string $volunteer_id_string
     * @return \Illuminate\Http\Response
     */
    public function edit_volunteer(Request $request, string $volunteer_id_string)
    {
        $volunteer_id = ctype_digit($volunteer_id_string) ? intval($volunteer_id_string) : null;
        if ($volunteer_id === null) {
            return redirect(route('cso.volunteers'));
        } else {
            $volunteer = Volunteer::where('organization_id', Auth::user()->organization_id)
                                  ->where('status', 'active')->find($volunteer_id);
            if (!$volunteer) {
                return redirect(route('cso.volunteers'));
            } else {
                return view('cso.edit_volunteer')->with([
                'volunteer' => $volunteer,
            ]);
            }
        }
    }

    /**
     * Handle "edit volunteer". - post
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit_volunteer_post(Request $request)
    {
        $volunteer_id = $request->all()['volunteer_id'];
        $volunteer = Volunteer::find($volunteer_id);

        if ($this->change_detected($request, $volunteer)) {
            if ($volunteer->email != $request->all()['volunteer-email']) {
                $validation = $this->volunteer_validator($request->all());
            } else {
                $validation = $this->volunteer_validator_update($request->all());
            }


            if ($validation->fails()) {
                // return redirect(route('cso.edit_volunteer'))->withErrors($validation->errors())
                //                                  ->withInput();
                return back()->withErrors($validation->errors())
                                                 ->withInput();
            }

            $file_id = $this->edit_handle_upload($request);
            $volunteer = $this->update_volunteer($volunteer, $request->all(), $file_id);
            return redirect(route('cso.volunteers'))->with('status', "Измените се успешно зачувани!");
        } else {
            return back();
        }
    }

    /**
     * Indicates if changes are being made to the volunteer information
     *
     * @param  Request  $request
     * @param  Volunteer $volunteer
     * @return bool
     */
    protected function change_detected(Request $request, Volunteer $volunteer)
    {
        $data = $request->all();

        if ($request->hasFile('volunteer-image')) {
            return true;
        }
        if ($volunteer->first_name != $data['volunteer-first-name']) {
            return true;
        }
        if ($volunteer->last_name != $data['volunteer-last-name']) {
            return true;
        }
        if ($volunteer->email != $data['volunteer-email']) {
            return true;
        }

        if ($volunteer->phone != $data['volunteer-phone']) {
            return true;
        }


        return false;
    }

    /**
     * updates the information for the profile
     *
     * @param  Volunteer $volunteer
     * @param  array  $data
     * @param  int  $file_id
     * @return FSR\Volunteer $volunteer
     */
    protected function update_volunteer(Volunteer $volunteer, array $data, $file_id)
    {
        if ($file_id) {
            $volunteer->image_id = $file_id;
        }
        $volunteer->first_name = $data['volunteer-first-name'];
        $volunteer->last_name = $data['volunteer-last-name'];
        $volunteer->email = $data['volunteer-email'];
        $volunteer->phone = $data['volunteer-phone'];

        $volunteer->save();

        return $volunteer;
    }
}
