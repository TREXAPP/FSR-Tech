<?php

namespace FSR\Http\Controllers\Donor;

use FSR\Comment;
use FSR\Listing;
use FSR\ListingOffer;
use FSR\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
     * @param int $listing_offer_id
     * @return void
     */
    public function single_listing_offer(Request $request, $listing_offer_id = null)
    {
        $listing_offer = ListingOffer::where('offer_status', 'active')
                                    ->whereHas('listing', function ($query) {
                                        $query->where('date_expires', '>', Carbon::now()->format('Y-m-d H:i'))
                                              ->where('donor_id', Auth::user()->id)
                                              ->where('listing_status', 'active');
                                    })->find($listing_offer_id);

        $comments = Comment::where('listing_offer_id', $listing_offer_id)
                            ->where('status', 'active')
                            ->orderBy('created_at', 'DESC')->get();

        if ($listing_offer) {
            return view('donor.my_accepted_listings')->with([
            'listing_offer' => $listing_offer,
            'comments' => $comments,
          ]);
        } else {
            //not ok, show error page
        }
    }

    /**
     * Handles post to this page
     *
     * @param  Request  $request
     * @param  int  $listing_offer_id
     * @return \Illuminate\Http\Response
     */
    public function single_listing_offer_post(Request $request, int $listing_offer_id = null)
    {
        //catch input-comment post
        if ($request->has('submit-comment')) {
            $comment = $this->create_comment($request->all(), $listing_offer_id);
            //  $comment->listing->donor->notify(new AcceptListing($listing_offer));
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
     * @param  int  $listing_offer_id
     * @return \FSR\Comment
     */
    protected function create_comment(array $data, int $listing_offer_id)
    {
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
}
