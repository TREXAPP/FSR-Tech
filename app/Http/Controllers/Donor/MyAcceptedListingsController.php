<?php

namespace FSR\Http\Controllers\Donor;

use FSR\Hub;
use FSR\Admin;
use FSR\HubDonorComment;
use FSR\Listing;
use FSR\HubListingOffer;
use FSR\Notifications;
use FSR\Notifications\DonorToHubComment;
use FSR\Notifications\DonorToAdminComment;
use FSR\Notifications\DonorToVolunteerComment;
use FSR\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;

class MyAcceptedListingsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:donor');
    }

    /**
     * Show a shigle listing offer
     * @param Request
     * @param int $hub_listing_offer_id
     * @return void
     */
    public function single_hub_listing_offer(Request $request, $hub_listing_offer_id = null)
    {
        $hub_listing_offer = HubListingOffer::where('status', 'active')
                                    ->whereHas('listing', function ($query) {
                                        $query->where('donor_id', Auth::user()->id)
                                              ->where('listing_status', 'active');
                                    })->find($hub_listing_offer_id);

        $comments = HubDonorComment::where('hub_listing_offer_id', $hub_listing_offer_id)
                            ->where('status', 'active')
                            ->orderBy('created_at', 'ASC')->get();
        $selected_filter = $this->get_selected_filter($hub_listing_offer);
        if ($hub_listing_offer) {
            return view('donor.my_accepted_listings')->with([
            'hub_listing_offer' => $hub_listing_offer,
            'comments' => $comments,
            'selected_filter' => $selected_filter,
          ]);
        } else {
            //not ok, show error page
        }
    }

    /**
     * Handles post to this page
     *
     * @param  Request  $request
     * @param  int  $hub_listing_offer_id
     * @return \Illuminate\Http\Response
     */
    public function single_hub_listing_offer_post(Request $request, int $hub_listing_offer_id = null)
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
     * Create a new comment instance after a valid input.
     *
     * @param  array  $data
     * @param  int  $hub_listing_offer_id
     * @return \FSR\Comment
     */
    protected function create_comment(array $data, int $hub_listing_offer_id)
    {
        $comment_text = $data['comment'];
        $hub_listing_offer = HubListingOffer::find($hub_listing_offer_id);
        $hub = $hub_listing_offer->hub;
        $other_comments = HubDonorComment::where('status', 'active')->where('hub_listing_offer_id', $hub_listing_offer_id)->get();

        $hub->notify(new DonorToHubComment($hub_listing_offer, $comment_text, $other_comments));

        //send to master_admin(s)
        $master_admins = Admin::where('master_admin', 1)
                          ->where('status', 'active')->get();
        Notification::send($master_admins, new DonorToAdminComment($hub_listing_offer, $comment_text, $other_comments));

        //find all regular admins that commented, and send them all
        $admin_comments = HubDonorComment::where('status', 'active')
                  ->where('hub_listing_offer_id', $hub_listing_offer_id)
                  ->where('sender_type', 'admin')
                  ->get();
        if ($admin_comments) {
            $admin_ids=array();
            $regular_admins = Admin::where('master_admin', 0)
                              ->where('status', 'active')->get();
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
                Admin::find($admin_id)->notify(new DonorToAdminComment($hub_listing_offer, $comment_text, $other_comments));
            }
        }

        return HubDonorComment::create([
            'hub_listing_offer_id' => $hub_listing_offer_id,
            'user_id' => Auth::user()->id,
            'sender_type' => Auth::user()->type(),
            'text' => $comment_text,
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
}
