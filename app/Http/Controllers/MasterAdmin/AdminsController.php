<?php

namespace FSR\Http\Controllers\MasterAdmin;

use FSR;
use FSR\File;
use FSR\Admin;
use FSR\Listing;
use FSR\FoodType;
use FSR\Location;
use FSR\Volunteer;
use FSR\ListingOffer;
use FSR\Organization;
use FSR\QuantityType;
use FSR\Custom\Methods;
use FSR\Notifications\AdminToVolunteerRemoved;

use FSR\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

class AdminsController extends Controller
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
    public function index()
    {
        $admins = Admin::where('status', 'active')->get();
        return $this->return_view($admins);
    }

    /**
     * return view
     *
     * @param FoodType
     * @param Product
     * @param Integer
     * @return \Illuminate\Http\Response
     */
    protected function return_view($admins)
    {
        return view('master_admin.admins')->with([
        'admins' => $admins,
      ]);
    }

    public function handle_post(Request $request)
    {
        $data = $request->all();
        if (!empty($data['post-type'])) {
            switch ($data['post-type']) {
          case 'delete':
            return $this->handle_delete($data);
          break;

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
        $admin = $this->delete($data);
        //$admin->notify(new AdminToAdminRemoved());
        return back()->with('status', "Администраторот е успешно избришан!");
    }

    /**
     * Mark the selected listing offer as cancelled
     *
     * @param  array  $data
     * @return \FSR\Volunteer
     */
    protected function delete(array $data)
    {
        $admin = Admin::find($data['admin_id']);
        $admin->status = 'deleted';
        $admin->save();
        return $admin;
    }
}
