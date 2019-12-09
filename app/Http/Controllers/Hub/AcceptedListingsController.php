<?php

namespace FSR\Http\Controllers\Hub;

use FSR\Donor;
use FSR\Hub;
use FSR\Admin;
use FSR\HubDonorComment;
use FSR\Listing;
use FSR\File;
use FSR\HubListingOffer;
use FSR\Custom\Methods;

use FSR\Http\Controllers\Controller;
use FSR\Custom\CarbonFix as Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use FSR\Notifications\HubToDonorCancelDonation;
use FSR\Notifications\HubToAdminCancelDonation;
use FSR\Notifications\HubToDonorComment;
use FSR\Notifications\HubToAdminDonorComment;

class AcceptedListingsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:hub');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $hub_listing_offers = HubListingOffer::select(DB::raw('hub_listing_offers.*'))
                                      ->join('listings', 'hub_listing_offers.listing_id', '=', 'listings.id')
                                      ->where('status', 'active')
                                      ->where('hub_id', Auth::user()->id)
                                      ->orderBy('date_expires', 'asc')
                                      ->whereHas('listing', function ($query) {
                                          $query->where('date_expires', '>', Carbon::now()->format('Y-m-d H:i'));
                                      });
        $comments = HubDonorComment::where('status', 'active')
                            ->orderBy('created_at', 'ASC')->get();
        //$hub_listing_offers = HubListingOffer::all();
        $hub_listing_offers_no = 0;
        foreach ($hub_listing_offers->get() as $hub_listing_offer) {
            $hub_listing_offers_no++;
        }

        $date_from = substr(Carbon::now()->addDays(-90), 0, 10);
        $date_to = substr(Carbon::now(), 0, 10);
        $date_to_date = Carbon::parse($date_to)->addDays(1);

        $selected_filter = 'active';

        return view('hub.accepted_listings')->with([
          'hub_listing_offers' => $hub_listing_offers,
          'hub_listing_offers_no' => $hub_listing_offers_no,
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
        // //  http://fsr.test/hub/active_listings#listingbox6
        // $route = route('hub.active_listings') . '#listingbox' . $request->all()['listing_id'];
        //
        // if ($validation->fails()) {
        //     return redirect($route)->withErrors($validation->errors())
        //                            ->withInput();
        // }
        // $hub_listing_offer = $this->create($request->all());
        // return back()->with('status', "Донацијата е успешно прифатена!");
    }


    /**
     * Handle insert new comment. (accepted listings list)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle_insert_comment(Request $request)
    {
        $hub_listing_offer = $this->create_comment($request->all(), $request->all()['hub_listing_offer_id']);
        return back()->with(['listingbox' => $request->all()['hub_listing_offer_id'],
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
        $hub_listing_offer = $this->edit_comment($request->all());
        return back()->with(['listingbox' => $request->all()['hub_listing_offer_id'],
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
        $hub_listing_offer = $this->delete_comment($request->all());
        return back()->with(['listingbox' => $request->all()['hub_listing_offer_id'],
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
        $hub = Auth::user();
        $hub_listing_offer = $this->delete_offer($request->all());
        $donor = Donor::find($hub_listing_offer->listing->donor_id);

       $donor->notify(new HubToDonorCancelDonation($hub_listing_offer));

        $master_admins = Admin::where('master_admin', 1)
                          ->where('status', 'active')->get();
        Notification::send($master_admins, new HubToAdminCancelDonation($hub_listing_offer, $hub, $donor));

        return back()->with('status', "Донацијата е успешно избришана!");
    }

    /**
     * Mark the selected listing offer as cancelled
     *
     * @param  array  $data
     * @return \FSR\HubListingOffer
     */
    protected function delete_offer(array $data)
    {
        $hub_listing_offer = HubListingOffer::find($data['hub_listing_offer_id']);
        $hub_listing_offer->status = 'cancelled';
        $hub_listing_offer->save();
        return $hub_listing_offer;
    }

    /**
     * Open a single listing offer page
     *
     * @param  Request  $request
     * @param  int  $hub_listing_offer_id
     * @return \Illuminate\Http\Response
     */
    public function single_accepted_listing(Request $request, $hub_listing_offer_id)
    {
        $hub_listing_offer = HubListingOffer::where('status', 'active')
                                   ->where('hub_id', Auth::user()->id)
                                   ->find($hub_listing_offer_id);
        if (!$hub_listing_offer) {
            return redirect(route('hub.accepted_listings'));
        } else {
            $comments = HubDonorComment::where('hub_listing_offer_id', $hub_listing_offer_id)
                                ->where('status', 'active')
                                ->orderBy('created_at', 'ASC')->get();
            $selected_filter = $this->get_selected_filter($hub_listing_offer);
            return view('hub.single_accepted_listing')->with([
            'hub_listing_offer' => $hub_listing_offer,
            'comments' => $comments,
            'selected_filter' => $selected_filter,
          ]);
        }
    }

    private function get_selected_filter($hub_listing_offer)
    {
        if ($hub_listing_offer->listing->listing_status == 'active') {
            if ($hub_listing_offer->listing->date_expires < Carbon::now()->format('Y-m-d H:i')) {
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
     * @param  int  $hub_listing_offer_id
     * @return \Illuminate\Http\Response
     */
    public function single_accepted_listing_post(Request $request, int $hub_listing_offer_id = null)
    {
        //catch input-comment post
        if ($request->has('submit-comment')) {
            $comment = $this->create_comment($request->all(), $hub_listing_offer_id);

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
     * Create a new hub_listing_offer instance after a valid input.
     *
     * @param  array  $data
     * @param  int  $hub_listing_offer_id
     * @return \FSR\Comment
     */
    protected function create_comment(array $data, int $hub_listing_offer_id)
    {
        $hub_listing_offer = HubListingOffer::find($hub_listing_offer_id);
        $donor = $hub_listing_offer->listing->donor;
        $comment_text = $data['comment'];
        $hub = Auth::user();
        $other_comments = HubDonorComment::where('status', 'active')->where('hub_listing_offer_id', $hub_listing_offer_id)->get();

        $hubDonorComment = HubDonorComment::create([
            'hub_listing_offer_id' => $hub_listing_offer_id,
            'user_id' => Auth::user()->id,
            'sender_type' => Auth::user()->type(),
            'text' => $data['comment'],
        ]);

        //send notification to the donor
       $donor->notify(new HubToDonorComment($hub_listing_offer, $comment_text, $other_comments));

        //send to master_admin(s)
        $master_admins = Admin::where('master_admin', 1)
                          ->where('status', 'active')->get();
        Notification::send($master_admins, new HubToAdminDonorComment($hub_listing_offer, $comment_text, $other_comments));

        //find all regular admins that commented, and send them all
        $admin_comments = HubDonorComment::where('status', 'active')
                  ->where('hub_listing_offer_id', $hub_listing_offer_id)
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
               Admin::find($admin_id)->notify(new HubToAdminDonorComment($hub_listing_offer, $comment_text, $other_comments));
            }
        }

        return $hubDonorComment;
    }


    /**
     * Mark the selected comment as deleted
     *
     * @param  array  $data
     * @return \FSR\Comment
     */
    protected function delete_comment(array $data)
    {
        $comment = HubDonorComment::find($data['comment_id']);
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
        $comment = HubDonorComment::find($data['comment_id']);
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

        $hub_listing_offers = HubListingOffer::select(DB::raw('hub_listing_offers.*'))
                                      ->join('listings', 'hub_listing_offers.listing_id', '=', 'listings.id')
                                      ->where('hub_listing_offers.status', 'active')
                                      ->where('hub_id', Auth::user()->id)
                                      ->orderBy('date_expires', 'asc')
                                      ->whereHas('listing', function ($query) use ($listing_status_operator, $date_from, $date_to_date) {
                                          $query->where('date_expires', $listing_status_operator, Carbon::now()->format('Y-m-d H:i'))
                                                ->where('date_listed', '>=', $date_from)
                                                ->where('date_listed', '<=', $date_to_date);
                                      });
        $comments = HubDonorComment::select(DB::raw('hub_donor_comments.*'))
                            ->join('hub_listing_offers', 'hub_donor_comments.hub_listing_offer_id', '=', 'hub_listing_offers.id')
                            ->where('hub_donor_comments.status', 'active')
                            ->orderBy('created_at', 'ASC')->get();

        $hub_listing_offers_no = 0;
        foreach ($hub_listing_offers->get() as $hub_listing_offer) {
            $hub_listing_offers_no++;
        }

        return view('hub.accepted_listings')->with([
          'hub_listing_offers' => $hub_listing_offers,
          'hub_listing_offers_no' => $hub_listing_offers_no,
          'comments' => $comments,
          'selected_filter' => $selected_filter,
          'date_from' => $date_from,
          'date_to' => $date_to,
        ]);
    }
}
