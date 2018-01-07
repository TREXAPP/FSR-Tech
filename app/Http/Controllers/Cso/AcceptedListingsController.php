<?php

namespace FSR\Http\Controllers\Cso;

use FSR\Comment;
use FSR\Listing;
use FSR\ListingOffer;
use FSR\Http\Controllers\Controller;
use FSR\Custom\CarbonFix as Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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


        // $listing_offers = ListingOffer::where('offer_status', 'active')
        //                               ->where('cso_id', Auth::user()->id)
        //                               ->whereHas('listing', function ($query) {
        //                                   $query->where('date_expires', '>', Carbon::now()->format('Y-m-d H:i'))
        //                               });

        $listing_offers = ListingOffer::select(DB::raw('listing_offers.*'))
                                      ->join('listings', 'listing_offers.listing_id', '=', 'listings.id')
                                      ->where('offer_status', 'active')
                                      ->where('cso_id', Auth::user()->id)
                                      ->orderBy('date_expires', 'asc')
                                      ->whereHas('listing', function ($query) {
                                          $query->where('date_expires', '>', Carbon::now()->format('Y-m-d H:i'));
                                      });
        //$listing_offers = ListingOffer::all();
        $listing_offers_no = 0;
        foreach ($listing_offers->get() as $listing_offer) {
            //     $quantity_counter = 0;
            //     foreach ($active_listing->listing_offers as $listing_offer) {
            //         $quantity_counter += $listing_offer->quantity;
            //     }
            //     if ($active_listing->quantity > $quantity_counter) {
            $listing_offers_no++;
            //     }
        }

        return view('cso.accepted_listings')->with([
          'listing_offers' => $listing_offers,
          'listing_offers_no' => $listing_offers_no,
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
        } elseif ($request->has('delete-offer-popup')) {
            return $this->handle_delete_offer($request);
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
        $listing_offer->volunteer_id = $data['volunteer'];
        $listing_offer->save();

        return response()->json(['listing-offer-id' => $listing_offer->id]);
    }


    /**
     * Handle insert new comment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle_insert_comment(Request $request)
    {
        $listing_offer = $this->single_accepted_listing_post($request, $request->all()['listing-offer-id']);
        return back()->with('listingbox', $request->all()['listing-offer-id']);
    }

    /**
     * Handle offer listing "delete". (it is actually update)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle_delete_offer(Request $request)
    {
        $listing_offer = $this->delete_offer($request->all());
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
    public function single_accepted_listing(Request $request, int $listing_offer_id)
    {
        $listing_offer = ListingOffer::where('offer_status', 'active')
                                   ->where('cso_id', Auth::user()->id)
                                   ->whereHas('listing', function ($query) {
                                       $query->where('date_expires', '>', Carbon::now()->format('Y-m-d H:i'))
                                            ->where('date_listed', '<=', Carbon::now()->format('Y-m-d H:i'))
                                            ->where('listing_status', 'active');
                                   })->find($listing_offer_id);
        if (!$listing_offer) {
            return redirect(route('cso.accepted_listings'));
        } else {
            $comments = Comment::where('listing_offer_id', $listing_offer_id)
                                ->where('status', 'active')
                                ->orderBy('created_at', 'DESC')->get();
            return view('cso.single_accepted_listing')->with([
            'listing_offer' => $listing_offer,
            'comments' => $comments,
          ]);
        }
    }

    /**
     * Open a single listing offer page
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
     * Create a new listing_offer instance after a valid input.
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
