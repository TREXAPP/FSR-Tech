<?php

namespace FSR\Http\Controllers\Admin;

use FSR\Cso;
use FSR\Donor;
use FSR\Volunteer;
use FSR\Organization;

use FSR\Notifications\AdminToAnyoneCustomEmail;

use Illuminate\Http\Request;
use FSR\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;

class EmailController extends Controller
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
        $csos = Cso::where('status', 'active');
        $donors = Donor::where('status', 'active');
        $volunteers = Volunteer::where('status', 'active')
                          ->where('is_user', '0');
        return view('admin.email')->with([
          'csos' => $csos,
          'donors' => $donors,
          'volunteers' => $volunteers
        ]);
    }

    public function handle_post(Request $request)
    {
        $data = $request->all();
        if (!empty($data['post-type'])) {
            switch ($data['post-type']) {
            case 'email':
              return $this->handle_email($request->all());
            break;
            default:
              return $this->index();
            break;
          }
        }
    }

    public function handle_email(array $data)
    {
        //dump($data);
        $validation = $this->validator($data);

        if ($validation->fails()) {
            return back()->withErrors($validation->errors())
              ->withInput();
        }

        $user_type_filter = (!empty($data['user-type-filter-select'])) ? $data['user-type-filter-select'] : null;
        $organization_filter = (!empty($data['organization-filter-select'])) ? $data['organization-filter-select'] : null;
        $user_filter = (!empty($data['user-filter-select'])) ? $data['user-filter-select'] : null;

        $send_to_donors = true;
        $send_to_csos = true;
        $send_to_volunteers = true;

        if ($user_type_filter) {
            switch ($user_type_filter) {
            case 'donors':
            $send_to_csos = false;
            $send_to_volunteers = false;

              if ($organization_filter) {
                  if ($user_filter) {
                      $donors = Donor::where('status', 'active')
                                    ->where('id', $user_filter);
                  } else {
                      $donors = Donor::where('status', 'active')
                                    ->where('organization_id', $organization_filter);
                  }
              } else {
                  $donors = Donor::where('status', 'active');
              }
              break;

            case 'csos':
            $send_to_donors = false;
            $send_to_volunteers = false;

            if ($organization_filter) {
                if ($user_filter) {
                    $csos = Cso::where('status', 'active')
                                  ->where('id', $user_filter);
                } else {
                    $csos = Cso::where('status', 'active')
                                  ->where('organization_id', $organization_filter);
                }
            } else {
                $csos = Cso::where('status', 'active');
            }
              break;
            case 'volunteers':
            $send_to_csos = false;
            $send_to_donors = false;

            if ($organization_filter) {
                if ($user_filter) {
                    $volunteers = Volunteer::where('status', 'active')
                                  ->where('is_user', '0')
                                  ->where('id', $user_filter);
                } else {
                    $volunteers = Volunteer::where('status', 'active')
                                  ->where('is_user', '0')
                                  ->where('organization_id', $organization_filter);
                }
            } else {
                $volunteers = Volunteer::where('status', 'active')
                                      ->where('is_user', '0');
            }
              break;

            default:
              $donors = Donor::where('status', 'active');
              $csos = Cso::where('status', 'active');
              $volunteers = Volunteer::where('status', 'active')
                                      ->where('is_user', '0');
              break;
          }
        } else {
            $donors = Donor::where('status', 'active');
            $csos = Cso::where('status', 'active');
            $volunteers = Volunteer::where('status', 'active')
                                  ->where('is_user', '0');
        }

        if ($send_to_donors) {
            Notification::send($donors->get(), new AdminToAnyoneCustomEmail($data['email-subject'], $data['email-message']));
        }
        if ($send_to_csos) {
            Notification::send($csos->get(), new AdminToAnyoneCustomEmail($data['email-subject'], $data['email-message']));
        }
        if ($send_to_volunteers) {
            Notification::send($volunteers->get(), new AdminToAnyoneCustomEmail($data['email-subject'], $data['email-message']));
        }

        return back()->with('status', "Пораката е успешно пратена!");
        //
      //
      // $listing_offer = $this->create($request->all());
      // $cso = Auth::user();
      // $donor = $listing_offer->listing->donor;
      //
      // $donor->notify(new CsoToDonorAcceptDonation($listing_offer));
      // if ($listing_offer->volunteer->email != Auth::user()->email) {
      //     $listing_offer->volunteer->notify(new CsoToVolunteerAcceptDonation($listing_offer, $cso, $donor));
      // }
      // return back()->with('status', "Донацијата е успешно прифатена!");
    }

    /**
     * Get a validator for an incoming listing offer input request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $validatorArray = [
            'email-subject' => 'required',
            'email-message' => 'required',
        ];

        return Validator::make($data, $validatorArray);
    }

    public function get_organizations(Request $request)
    {
        $data = $request->all();
        if ($data['users_type']) {
            switch ($data['users_type']) {
          case 'csos':
            $organizations = Organization::where('type', 'cso')
                        ->where('status', 'active')->get();
            return response()->json($organizations);
            break;
          case 'donors':
          $organizations = Organization::where('type', 'donor')
                      ->where('status', 'active')->get();
          return response()->json($organizations);
          break;
          case 'volunteers':
            $organizations = Organization::where('type', 'cso')
                        ->where('status', 'active')->get();
            return response()->json($organizations);
            break;

          default:
            return '';
            break;
        }
        } else {
            return '';
        }
    }

    public function get_users(Request $request)
    {
        $data = $request->all();
        if ($data['organization_id']) {
            switch ($data['users_type']) {
            case "csos":
            $csos = Cso::where('organization_id', $data['organization_id'])
                        ->where('status', 'active')->get();
            return response()->json($csos);
            break;
            case "volunteers":
            $volunteers = Volunteer::where('organization_id', $data['organization_id'])
                        ->where('is_user', '0')
                        ->where('status', 'active')->get();
            return response()->json($volunteers);
            break;
            case "donors":
            $donors = Donor::where('organization_id', $data['organization_id'])
                        ->where('status', 'active')->get();
            return response()->json($donors);
            break;
          default:
            return '';
            break;
        }
        } else {
            return '';
        }
    }

    public function get_counters(Request $request)
    {
        $csos_counter = Cso::where('status', 'active')->count();
        $donors_counter = Donor::where('status', 'active')->count();
        $volunteers_counter = Volunteer::where('status', 'active')
                            ->where('is_user', '0')->count();

        $data = $request->all();
        if ($data['users_type']) {
            switch ($data['users_type']) {
            case 'donors':
              $csos_counter = 0;
              $volunteers_counter = 0;

              if ($data['organization_id']) {
                  if ($data['user_id']) {
                      $donors_counter = 1;
                  } else {
                      $donors_counter = Donor::where('status', 'active')
                                      ->where('organization_id', $data['organization_id'])->count();
                  }
              } else {
                  //ok
              }
              break;

            case 'csos':
            $donors_counter = 0;
            $volunteers_counter = 0;

            if ($data['organization_id']) {
                if ($data['user_id']) {
                    $csos_counter = 1;
                } else {
                    $csos_counter = Cso::where('status', 'active')
                                    ->where('organization_id', $data['organization_id'])->count();
                }
            } else {
                //ok
            }
              break;
            case 'volunteers':
            $donors_counter = 0;
            $csos_counter = 0;

            if ($data['organization_id']) {
                if ($data['user_id']) {
                    $volunteers_counter = 1;
                } else {
                    $volunteers_counter = Volunteer::where('status', 'active')
                                        ->where('is_user', '0')
                                    ->where('organization_id', $data['organization_id'])->count();
                }
            } else {
                //ok
            }
              break;

            default:
              //zemi gi site
              break;
          }
        } else {
            //zemi gi site
        }

        // $csos_counter = $csos->count();
        // $donors_counter = $donors->count();
        // $volunteers_counter = $volunteers->count();

        return response()->json([
          'csos_counter' => $csos_counter,
          'donors_counter' => $donors_counter,
          'volunteers_counter' => $volunteers_counter,
        ]);
    }
}
