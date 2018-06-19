<?php

namespace FSR\Http\Controllers\Cso;

use FSR\Cso;
use FSR\Listing;
use FSR\File;
use FSR\Admin;
use FSR\Volunteer;
use FSR\ListingOffer;
use FSR\Notifications\CsoToVolunteerNewVolunteer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

use FSR\Http\Controllers\Controller;
use FSR\Notifications;
use FSR\Notifications\CsoToVolunteerAcceptDonation;
use FSR\Notifications\CsoToAdminAcceptDonation;
use FSR\Notifications\CsoToDonorAcceptDonation;
use FSR\Notifications\CsoToCsoAcceptDonation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use FSR\Custom\Methods;
use FSR\Custom\CarbonFix;

class ChangePasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:cso');
    }


    public function index()
    {
        return view('cso.change_password');
    }

    public function handle_post(Request $request)
    {
        return $this->changePassword($request);
    }

    public function changePassword(Request $request)
    {
        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error", "Моменталната лозинка не е точна. Обидете се повторно.");
        }
        if (strcmp($request->get('current-password'), $request->get('new-password')) == 0) {
            //Current password and new password are same
            return redirect()->back()->with("error", "Новата лозинка не смее да биде иста со моменталната. Изберете друга лозинка.");
        }
        $validatedData = $request->validate([
            'current-password' => 'required',
            'new-password' => 'required|string|min:6|confirmed',
            ]);
        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();
        return redirect()->back()->with("success", "Лозинката е успешно променета!");
    }
}
