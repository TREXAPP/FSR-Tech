<?php

namespace FSR\Http\Controllers\Admin;

use FSR\Cso;
use FSR\Donor;
use FSR\Hub;
use FSR\Volunteer;
use FSR\Custom\Methods;

use FSR\Notifications\AdminToCsoApproveRegistration;
use FSR\Notifications\AdminToUserRejectRegistration;
use FSR\Notifications\AdminToDonorApproveRegistration;
use FSR\Notifications\AdminToHubApproveRegistration;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use FSR\Http\Controllers\Controller;

class ApproveUsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //Route::group(['middleware' => ['auth:biker,customer,operator'], function() {}
        $this->middleware('auth:master_admin,admin');
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
        $hubs = Hub::where('status', 'pending')
                       ->whereHas('organization', function ($query) {
                           $query->where('status', 'active');
                       })->get();
        return view('admin.approve_users')->with([
          'csos' => $csos,
          'donors' => $donors,
          'hubs' => $hubs,
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
        if ($request->has('approve-cso')) {
            return $this->handle_approve_cso($request->all());
        } elseif ($request->has('approve-donor')) {
            return $this->handle_approve_donor($request->all());
        } elseif ($request->has('approve-hub')) {
            return $this->handle_approve_hub($request->all());
        } elseif ($request->has('reject-cso')) {
            return $this->handle_reject_cso($request->all());
        } elseif ($request->has('reject-donor')) {
            return $this->handle_reject_donor($request->all());
        } elseif ($request->has('reject-hub')) {
            return $this->handle_reject_hub($request->all());
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
            Methods::log_event('admin_approve', $cso->id, 'cso');
            $this->approve_volunteer($data['cso_id']);
            $cso->notify(new AdminToCsoApproveRegistration($cso));

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
            Methods::log_event('admin_approve', $donor->id, 'donor');
            $donor->notify(new AdminToDonorApproveRegistration($donor));

            return back()->with([
            'status' => 'Донаторот е успешно одобрен!'
          ]);
        }
        return back();
    }

    /**
     * Handle approve hub
     *
     * @param  Collection  $data
     * @return \Illuminate\Http\Response
     */
    public function handle_approve_hub(array $data)
    {
        $hub = Hub::find($data['hub_id']);
        if ($hub) {
            $hub->status = 'active';
            $hub->save();
            Methods::log_event('admin_approve', $hub->id, 'hub');
            $hub->notify(new AdminToHubApproveRegistration($hub));

            return back()->with([
            'status' => 'Хабот е успешно одобрен!'
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
            Methods::log_event('admin_deny', $cso->id, 'cso');
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
            Methods::log_event('admin_deny', $donor->id, 'donor');
            $donor->notify(new AdminToUserRejectRegistration());
            return back()->with([
            'status' => 'Донаторот е одбиен!'
          ]);
        }
        return back();
    }

    /**
     * Handle reject hub
     *
     * @param  Collection  $data
     * @return \Illuminate\Http\Response
     */
    public function handle_reject_hub(array $data)
    {
        $hub = Hub::find($data['hub_id']);
        if ($hub) {
            $hub->status = 'rejected';
            $hub->save();
            Methods::log_event('admin_deny', $hub->id, 'hub');
            $hub->notify(new AdminToUserRejectRegistration());
            return back()->with([
            'status' => 'Хабот е одбиен!'
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
