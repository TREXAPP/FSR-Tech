<?php

namespace FSR\Http\Controllers\Admin;

use FSR;
use FSR\File;
use FSR\Listing;
use FSR\Location;
use FSR\FoodType;
use FSR\ListingOffer;
use FSR\Custom\Methods;
use FSR\Notifications\CsoToFoodTypeRemoved;

use FSR\Http\Controllers\Controller;
use FSR\Notifications\CsoToFoodTypeNewFoodType;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

class EditFoodTypeController extends Controller
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
    public function index(Request $request, string $food_type_id_string)
    {
        $food_type_id = ctype_digit($food_type_id_string) ? intval($food_type_id_string) : null;
        if ($food_type_id === null) {
            return redirect(route('admin.food_types'));
        } else {
            $food_type = FoodType::find($food_type_id);
            if (!$food_type) {
                return redirect(route('admin.food_types'));
            } else {
                return view('admin.edit_food_type')->with([
              'food_type' => $food_type,
          ]);
            }
        }
    }


    /**
     * set information for image upload for editing an existing food_type
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function edit_handle_upload(Request $request)
    {
        $input_name = 'food-type-image';
        $purpose = 'food_type image';
        $for_user_type = 'admin';
        $description = 'A food type image uploaded when admin edits food type';

        return Methods::handleUpload($request, $input_name, $purpose, $for_user_type, $description);
    }

    /**
     * Get a validator for a new food_type insert
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $validatorArray = [
                    'food-type-name'    => 'required',
                    'food-type-image'         => 'image|max:2048',
                ];

        return Validator::make($data, $validatorArray);
    }


    /**
     * Handle "edit food_type". - post
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle_post(Request $request)
    {
        $food_type_id = $request->all()['food_type_id'];
        $food_type = FoodType::find($food_type_id);

        if ($this->change_detected($request, $food_type)) {
            $validation = $this->validator($request->all());

            if ($validation->fails()) {
                return back()->withErrors($validation->errors())
                                                 ->withInput();
            }

            $file_id = $this->edit_handle_upload($request);
            $food_type = $this->update($food_type, $request->all(), $file_id);
            return redirect(route('admin.food_types'))->with('status', "Измените се успешно зачувани!");
        } else {
            return back();
        }
    }

    /**
     * Indicates if changes are being made to the food_type information
     *
     * @param  Request  $request
     * @param  FoodType $food_type
     * @return bool
     */
    protected function change_detected(Request $request, FoodType $food_type)
    {
        $data = $request->all();

        if ($request->hasFile('food-type-image')) {
            return true;
        }
        if ($food_type->name != $data['food-type-name']) {
            return true;
        }
        if ($food_type->description != $data['food-type-description']) {
            return true;
        }


        return false;
    }

    /**
     * updates the information for the profile
     *
     * @param  FoodType $food_type
     * @param  array  $data
     * @param  int  $file_id
     * @return FSR\FoodType $food_type
     */
    protected function update(FoodType $food_type, array $data, $file_id)
    {
        if ($file_id) {
            $food_type->image_id = $file_id;
        }
        $food_type->name = $data['food-type-name'];
        $food_type->description = $data['food-type-description'];

        $food_type->save();

        return $food_type;
    }
}
