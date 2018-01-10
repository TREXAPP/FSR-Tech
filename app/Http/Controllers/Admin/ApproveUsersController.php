<?php

namespace FSR\Http\Controllers\Admin;

use FSR\Cso;
use FSR\Donor;
use FSR\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

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
        $csos = Cso::where('status', 'pending')->get();
        $donors = Donor::where('status', 'pending')->get();
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

            return back()->with([
            'status' => 'Донорот е одбиен!'
          ]);
        }
        return back();
    }
}
