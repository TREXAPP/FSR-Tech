<?php

namespace FSR\Http\Controllers\Cso;

use FSR\Donor;
use FSR\Admin;
use FSR\Comment;
use FSR\Listing;
use FSR\File;
use FSR\Volunteer;
use FSR\ListingOffer;
use FSR\Custom\Methods;

use FSR\Notifications\CsoToVolunteerComment;
use FSR\Notifications\CsoToNewVolunteerChanged;
use FSR\Notifications\CsoToOldVolunteerChanged;
use FSR\Notifications\CsoToDonorVolunteerChanged;
use FSR\Notifications\CsoToVolunteerCancelDonation;
use FSR\Notifications\CsoToAdminCancelDonation;
use FSR\Notifications\CsoToAdminComment;

use FSR\Http\Controllers\Controller;
use FSR\Custom\CarbonFix as Carbon;
use FSR\Notifications\CsoToDonorCancelDonation;
use FSR\Notifications\CsoToDonorComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;

class AcceptedListingsController extends Controller
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
        $listing_offers = ListingOffer::select(DB::raw('listing_offers.*'))
                                      ->join('listings', 'listing_offers.listing_id', '=', 'listings.id')
                                      ->where('offer_status', 'active')
                                      ->where('cso_id', Auth::user()->id)
                                      ->orderBy('date_expires', 'asc')
                                      ->whereHas('listing', function ($query) {
                                          $query->where('date_expires', '>', Carbon::now()->format('Y-m-d H:i'));
                                      });
        $comments = Comment::where('status', 'active')
                            ->orderBy('created_at', 'ASC')->get();
        //$listing_offers = ListingOffer::all();
        $listing_offers_no = 0;
        foreach ($listing_offers->get() as $listing_offer) {
            $listing_offers_no++;
        }

        $date_from = substr(Carbon::now()->addDays(-90), 0, 10);
        $date_to = substr(Carbon::now(), 0, 10);
        $date_to_date = Carbon::parse($date_to)->addDays(1);

        $selected_filter = 'active';

        return view('cso.accepted_listings')->with([
          'listing_offers' => $listing_offers,
          'listing_offers_no' => $listing_offers_no,
          'comments' => $comments,
          'selected_filter' => $selected_filter,
          'date_from' => $date_from,
          'date_to' => $date_to,
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
        if ($request->has('submit-comment')) {
            return $this->handle_insert_comment($request);
        } elseif ($request->has('delete-comment')) {
            return $this->handle_delete_comment($request);
        } elseif ($request->has('edit-comment')) {
            return $this->handle_edit_comment($request);
        } elseif ($request->has('delete-offer-popup')) {
            return $this->handle_delete_offer($request);
        } elseif ($request->has('filter-submit')) {
            return $this->handle_filter($request);
        }

        // $validation = $this->validator($request->all());
        //
        // //  http://fsr.test/cso/active_listings#listingbox6
        // $route = route('cso.active_listings') . '#listingbox' . $request->all()['listing_id'];
        //
        // if ($validation->fails()) {
        //     return redirect($route)->withErrors($validation->errors())
        //                            ->withInput();
        // }
        // $listing_offer = $this->create($request->all());
        // return back()->with('status', "Донацијата е успешно прифатена!");
    }

    /**
     * Handle volunteer update.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update_volunteer(Request $request)
    {
        // $validation = $this->validator($request->all());
        //
        // if ($validation->fails()) {
        //     return redirect($route)->withErrors($validation->errors())
        //                          ->withInput();
        // }


        $data = $request->all();
        $listing_offer = ListingOffer::find($data['listing_offer_id']);
        $cso = Auth::user();
        $old_volunteer = Volunteer::find($listing_offer->volunteer_id);
        $listing_offer->volunteer_id = $data['volunteer'];
        $new_volunteer = Volunteer::find($data['volunteer']);
        $donor = $listing_offer->listing->donor;
        $listing_offer->save();

        //TODO 3 email notifications: donor, new volunteer and old volunteer
        $donor->notify(new CsoToDonorVolunteerChanged($listing_offer, $new_volunteer));
        $new_volunteer->notify(new CsoToNewVolunteerChanged($listing_offer, $cso, $donor));
        $old_volunteer->notify(new CsoToOldVolunteerChanged($listing_offer, $cso, $donor));

        $image_url = Methods::get_volunteer_image_url($listing_offer->volunteer);

        return response()->json([
          'listing-offer-id' => $listing_offer->id,
          'volunteer_first_name' => $listing_offer->volunteer->first_name,
          'volunteer_last_name' => $listing_offer->volunteer->last_name,
          'volunteer_phone' => $listing_offer->volunteer->phone,
          'volunteer_email' => $listing_offer->volunteer->email,
          'volunteer_image_url' => $image_url,
        ]);
    }


    /**
     * Handle insert new comment. (accepted listings list)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle_insert_comment(Request $request)
    {
        $listing_offer = $this->create_comment($request->all(), $request->all()['listing_offer_id']);
        return back()->with(['listingbox' => $request->all()['listing_offer_id'],
                             'status' => 'Коментарот е внесен!']);
    }

    /**
     * Handle edit comment. (accepted listings list)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle_edit_comment(Request $request)
    {
        $listing_offer = $this->edit_comment($request->all());
        return back()->with(['listingbox' => $request->all()['listing_offer_id'],
                             'status' => 'Коментарот е изменет!']);
    }

    /**
     * Handle delete comment.(accepted listings list)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle_delete_comment(Request $request)
    {
        $listing_offer = $this->delete_comment($request->all());
        return back()->with(['listingbox' => $request->all()['listing_offer_id'],
                             'status' => 'Коментарот е избришан!']);
        ;
    }

    /**
     * Handle offer listing "delete". (it is actually update)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle_delete_offer(Request $request)
    {
        $cso = Auth::user();
        $listing_offer = $this->delete_offer($request->all());
        $volunteer = $listing_offer->volunteer;
        $donor = Donor::find($listing_offer->listing->donor_id);
        $donor->notify(new CsoToDonorCancelDonation($listing_offer));
        $volunteer->notify(new CsoToVolunteerCancelDonation($listing_offer, $cso, $donor));

        $master_admins = Admin::where('master_admin', 1)
                          ->where('status', 'active')->get();
        Notification::send($master_admins, new CsoToAdminCancelDonation($listing_offer, $cso, $donor));

        return back()->with('status', "Донацијата е успешно избришана!");
    }

    /**
     * Mark the selected listing offer as cancelled
     *
     * @param  array  $data
     * @return \FSR\ListingOffer
     */
    protected function delete_offer(array $data)
    {
        $listing_offer = ListingOffer::find($data['listing_offer_id']);
        $listing_offer->offer_status = 'cancelled';
        $listing_offer->save();
        return $listing_offer;
    }

    /**
     * Open a single listing offer page
     *
     * @param  Request  $request
     * @param  int  $listing_offer_id
     * @return \Illuminate\Http\Response
     */
    public function single_accepted_listing(Request $request, $listing_offer_id)
    {
        $listing_offer = ListingOffer::where('offer_status', 'active')
                                   ->where('cso_id', Auth::user()->id)
                                   ->find($listing_offer_id);
        if (!$listing_offer) {
            return redirect(route('cso.accepted_listings'));
        } else {
            $comments = Comment::where('listing_offer_id', $listing_offer_id)
                                ->where('status', 'active')
                                ->orderBy('created_at', 'ASC')->get();
            $selected_filter = $this->get_selected_filter($listing_offer);
            return view('cso.single_accepted_listing')->with([
            'listing_offer' => $listing_offer,
            'comments' => $comments,
            'selected_filter' => $selected_filter,
          ]);
        }
    }

    private function get_selected_filter($listing_offer)
    {
        if ($listing_offer->listing->listing_status == 'active') {
            if ($listing_offer->listing->date_expires < Carbon::now()->format('Y-m-d H:i')) {
                return 'past';
            } else {
                return 'active';
            }
        } else {
            return 'past';
        }
    }

    /**
     * Handles post to this page
     *
     * @param  Request  $request
     * @param  int  $listing_offer_id
     * @return \Illuminate\Http\Response
     */
    public function single_accepted_listing_post(Request $request, int $listing_offer_id = null)
    {
        //catch input-comment post
        if ($request->has('submit-comment')) {
            $comment = $this->create_comment($request->all(), $listing_offer_id);

            return back();
        }

        if ($request->has('delete-comment')) {
            $comment = $this->delete_comment($request->all());
            return back()->with('status', "Коментарот е избришан!");
        }

        if ($request->has('edit-comment')) {
            $comment = $this->edit_comment($request->all());
            return back()->with('status', "Коментарот е изменет!");
        }

        //za drugite:
        // if $request->has('edit-comment-9')
    }

    /**
     * Create a new listing_offer instance after a valid input.
     *
     * @param  array  $data
     * @param  int  $listing_offer_id
     * @return \FSR\Comment
     */
    protected function create_comment(array $data, int $listing_offer_id)
    {
        $listing_offer = ListingOffer::find($listing_offer_id);
        $donor = $listing_offer->listing->donor;
        $volunteer = Volunteer::find($listing_offer->volunteer_id);
        $comment_text = $data['comment'];
        $cso = Auth::user();
        $other_comments = Comment::where('status', 'active')->where('listing_offer_id', $listing_offer_id)->get();

        //send notification to the donor
        $donor->notify(new CsoToDonorComment($listing_offer, $comment_text, $other_comments));
        //send notification to the volunteer
        if ($cso->email != $volunteer->email) {
            $volunteer->notify(new CsoToVolunteerComment($listing_offer, $comment_text, $other_comments));
        }


        //send to master_admin(s)
        $master_admins = Admin::where('master_admin', 1)
                          ->where('status', 'active')->get();
        Notification::send($master_admins, new CsoToAdminComment($listing_offer, $comment_text, $other_comments));

        //find all regular admins that commented, and send them all
        $admin_comments = Comment::where('status', 'active')
                  ->where('listing_offer_id', $listing_offer_id)
                  ->where('sender_type', 'admin')
                  ->get();
        if ($admin_comments) {
            $admin_ids=array();
            $regular_admins = Admin::where('master_admin', 0)->where('status', 'active')->get();
            foreach ($regular_admins as $admin) {
                foreach ($admin_comments as $admin_comment) {
                    if ($admin_comment->user_id == $admin->id) {
                        if (!in_array($admin->id, $admin_ids)) {
                            $admin_ids[]=$admin->id;
                        }
                    }
                }
            }
            foreach ($admin_ids as $admin_id) {
                Admin::find($admin_id)->notify(new CsoToAdminComment($listing_offer, $comment_text, $other_comments));
            }
        }

        return Comment::create([
            'listing_offer_id' => $listing_offer_id,
            'user_id' => Auth::user()->id,
            'sender_type' => Auth::user()->type(),
            'text' => $data['comment'],
        ]);
    }


    /**
     * Mark the selected comment as deleted
     *
     * @param  array  $data
     * @return \FSR\Comment
     */
    protected function delete_comment(array $data)
    {
        $comment = Comment::find($data['comment_id']);
        $comment->status = 'deleted';
        $comment->save();
        return $comment;
    }

    /**
     * Edit the selected comment text
     *
     * @param  array  $data
     * @return \FSR\Comment
     */
    protected function edit_comment(array $data)
    {
        $comment = Comment::find($data['comment_id']);
        $comment->text = $data['edit_comment_text'];
        $comment->save();
        return $comment;
    }


    /**
     * Handle offer listing "filter".
     *
     * @param  Array $data
     * @return \Illuminate\Http\Response
     */
    public function handle_filter(Request $request)
    {
        $data = $request->all();

        $date_from = $data["filter_date_from"];
        $date_to = $data["filter_date_to"];
        $date_to_date = Carbon::parse($date_to)->addDays(1);

        $selected_filter = $data["donations-filter-select"];

        switch ($selected_filter) {
          case 'active':
            $listing_status_operator = ">";
            break;
          case 'past':
            $listing_status_operator = "<";
            break;

          default:
            $listing_status_operator = ">";
            break;
        }

        $listing_offers = ListingOffer::select(DB::raw('listing_offers.*'))
                                      ->join('listings', 'listing_offers.listing_id', '=', 'listings.id')
                                      ->where('offer_status', 'active')
                                      ->where('cso_id', Auth::user()->id)
                                      ->orderBy('date_expires', 'asc')
                                      ->whereHas('listing', function ($query) use ($listing_status_operator, $date_from, $date_to_date) {
                                          $query->where('date_expires', $listing_status_operator, Carbon::now()->format('Y-m-d H:i'))
                                                ->where('date_listed', '>=', $date_from)
                                                ->where('date_listed', '<=', $date_to_date);
                                      });
        $comments = Comment::select(DB::raw('comments.*'))
                            ->join('listing_offers', 'comments.listing_offer_id', '=', 'listing_offers.id')
                            ->where('status', 'active')
                            ->orderBy('created_at', 'ASC')->get();

        $listing_offers_no = 0;
        foreach ($listing_offers->get() as $listing_offer) {
            $listing_offers_no++;
        }

        return view('cso.accepted_listings')->with([
          'listing_offers' => $listing_offers,
          'listing_offers_no' => $listing_offers_no,
          'comments' => $comments,
          'selected_filter' => $selected_filter,
          'date_from' => $date_from,
          'date_to' => $date_to,
        ]);
    }
}
