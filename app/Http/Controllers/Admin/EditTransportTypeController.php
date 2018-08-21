<?php

namespace FSR\Http\Controllers\Admin;

use FSR;
use FSR\File;
use FSR\Listing;
use FSR\TransportType;
use FSR\ListingOffer;
use FSR\Custom\Methods;

use FSR\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

class EditTransportTypeController extends Controller
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
    public function index(Request $request, string $transport_type_id_string)
    {
        $transport_type_id = ctype_digit($transport_type_id_string) ? intval($transport_type_id_string) : null;
        if ($transport_type_id === null) {
            return redirect(route('admin.transport_types'));
        } else {
            $transport_type = TransportType::find($transport_type_id);
            if (!$transport_type) {
                return redirect(route('admin.transport_types'));
            } else {
                return view('admin.edit_transport_type')->with([
              'transport_type' => $transport_type,
          ]);
            }
        }
    }

    /**
     * Get a validator for a new transport_type insert
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $validatorArray = [
                    'name'    => 'required',
                ];

        return Validator::make($data, $validatorArray);
    }

    /**
     * Handle "edit transport_type". - post
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle_post(Request $request)
    {
        $transport_type_id = $request->all()['transport_type_id'];
        $transport_type = TransportType::find($transport_type_id);

        if ($this->change_detected($request, $transport_type)) {
            $validation = $this->validator($request->all());


            if ($validation->fails()) {
                return back()->withErrors($validation->errors())
                                                 ->withInput();
            }

            $transport_type = $this->update($transport_type, $request->all());
            return redirect(route('admin.transport_types'))->with('status', "Измените се успешно зачувани!");
        } else {
            return back();
        }
    }

    /**
     * Indicates if changes are being made to the transport_type information
     *
     * @param  Request  $request
     * @param  TransportType $transport_type
     * @return bool
     */
    protected function change_detected(Request $request, $transport_type)
    {
        $data = $request->all();

        if ($transport_type->name != $data['name']) {
            return true;
        }
        if ($transport_type->quantity != $data['quantity']) {
            return true;
        }
        if ($transport_type->comment != $data['comment']) {
            return true;
        }

        return false;
    }

    /**
     * updates the information for the profile
     *
     * @param  TransportType $transport_type
     * @param  array  $data
     * @return FSR\TransportType $transport_type
     */
    protected function update(TransportType $transport_type, array $data)
    {
        $transport_type->name = $data['name'];
        $transport_type->quantity = $data['quantity'];
        $transport_type->comment = $data['comment'];

        $transport_type->save();

        return $transport_type;
    }
}
