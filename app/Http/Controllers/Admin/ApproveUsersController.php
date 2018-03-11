<?php

namespace FSR\Http\Controllers\Admin;

use FSR\Cso;
use FSR\Donor;
use FSR\Volunteer;
use FSR\Notifications\AdminToUserRejectRegistration;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use FSR\Http\Controllers\Controller;
use FSR\Notifications\AdminToUserApproveRegistration;

class ApproveUsersController extends Controller
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
        $csos = Cso::where('status', 'pending')
                    ->whereHas('organization', function ($query) {
                        $query->where('status', 'active');
                    })->get();
        $donors = Donor::where('status', 'pending')
                       ->whereHas('organization', function ($query) {
                           $query->where('status', 'active');
                       })->get();
        return view('admin.approve_users')->with([
          'csos' => $csos,
          'donors' => $donors,
        ]);
        //return redirect(route('donor.my_active_listings'));
    }

    /**
     * Handle post request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle_post(Request $request)
    {
        if ($request->has('approve-cso')) {
            return $this->handle_approve_cso($request->all());
        } elseif ($request->has('approve-donor')) {
            return $this->handle_approve_donor($request->all());
        } elseif ($request->has('reject-cso')) {
            return $this->handle_reject_cso($request->all());
        } elseif ($request->has('reject-donor')) {
            return $this->handle_reject_donor($request->all());
        }
    }

    /**
     * Handle approve cso
     *
     * @param  Collection  $data
     * @return \Illuminate\Http\Response
     */
    public function handle_approve_cso(array $data)
    {
        $cso = Cso::find($data['cso_id']);
        if ($cso) {
            $cso->status = 'active';
            $cso->save();
            $this->approve_volunteer($data['cso_id']);
            $cso->notify(new AdminToUserApproveRegistration($cso));

            return back()->with([
            'status' => 'Примателот е успешно одобрен!'
          ]);
        }
        return back();
    }

    /**
     * Handle approve donor
     *
     * @param  Collection  $data
     * @return \Illuminate\Http\Response
     */
    public function handle_approve_donor(array $data)
    {
        $donor = Donor::find($data['donor_id']);
        if ($donor) {
            $donor->status = 'active';
            $donor->save();
            $donor->notify(new AdminToUserApproveRegistration($donor));

            return back()->with([
            'status' => 'Донорот е успешно одобрен!'
          ]);
        }
        return back();
    }

    /**
     * Handle reject cso
     *
     * @param  Collection  $data
     * @return \Illuminate\Http\Response
     */
    public function handle_reject_cso(array $data)
    {
        $cso = Cso::find($data['cso_id']);
        if ($cso) {
            $cso->status = 'rejected';
            $cso->save();
            $cso->notify(new AdminToUserRejectRegistration());
            return back()->with([
            'status' => 'Примателот е одбиен!'
          ]);
        }
        return back();
    }

    /**
     * Handle reject donor
     *
     * @param  Collection  $data
     * @return \Illuminate\Http\Response
     */
    public function handle_reject_donor(array $data)
    {
        $donor = Donor::find($data['donor_id']);
        if ($donor) {
            $donor->status = 'rejected';
            $donor->save();
            $donor->notify(new AdminToUserRejectRegistration());
            return back()->with([
            'status' => 'Донорот е одбиен!'
          ]);
        }
        return back();
    }


    /**
     * Approve the volunteer instance for the cso
     *
     * @param  int $id
     * @return void
     */
    public function approve_volunteer($id)
    {
        $volunteer = Volunteer::where('is_user', '1')
                                ->where('added_by_user_id', $id)->first();
        if ($volunteer) {
            $volunteer->status = 'active';
            $volunteer->save();
        }
    }
}
