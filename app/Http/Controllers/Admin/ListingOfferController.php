<?php

namespace FSR\Http\Controllers\Admin;

use FSR;
use FSR\Cso;
use FSR\File;
use FSR\Listing;
use FSR\Location;
use FSR\Volunteer;
use FSR\ListingOffer;
use FSR\Comment;
use FSR\Organization;
use FSR\Custom\Methods;
use FSR\Notifications\AdminToVolunteerRemoved;

use FSR\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;

class ListingOfferController extends Controller
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
     * Open a single listing offer page
     *
     * @param  Request  $request
     * @param  int  $listing_offer_id
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $listing_offer_id)
    {
        $listing_offer = ListingOffer::find($listing_offer_id);
        if (!$listing_offer) {
            return redirect(route('admin.listings'));
        } else {
            $comments = Comment::where('listing_offer_id', $listing_offer_id)
                            ->where('status', 'active')
                            ->orderBy('created_at', 'ASC')->get();
            return view('admin.accepted_listing')->with([
        'listing_offer' => $listing_offer,
        'comments' => $comments,
      ]);
        }
    }


    /**
     * Handle post request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle_post(Request $request, $listing_offer_id)
    {
        $data = $request->all();
        if (!empty($data['post-type'])) {
            switch ($data['post-type']) {
                case 'edit_quantity':
                  return $this->handle_edit_quantity($data, $listing_offer_id);
                case 'update_volunteer':
                  return $this->handle_update_volunteer($data, $listing_offer_id);
                case 'delete_listing_offer':
                  return $this->handle_delete_listing_offer($data, $listing_offer_id);
                case 'delete_comment':
                  return $this->handle_delete_comment($data, $listing_offer_id);
                case 'edit_comment':
                  return $this->handle_edit_comment($data, $listing_offer_id);
                case 'new_comment':
                  return $this->handle_insert_comment($data, $listing_offer_id);
                default:
                  return $this->index(null, $listing_offer_id);
                break;
              }
        }
    }

    protected function handle_insert_comment(array $data, int $listing_offer_id)
    {
        $listing_offer = $this->create_comment($data, $listing_offer_id);
        return back()->with(['status' => 'Коментарот е внесен!']);
    }

    protected function create_comment(array $data, int $listing_offer_id)
    {
        $listing_offer = ListingOffer::find($listing_offer_id);
        $donor = $listing_offer->listing->donor;
        $volunteer = Volunteer::find($listing_offer->volunteer_id);
        $comment_text = $data['comment'];
        $cso = $listing_offer->cso;

        //TODO - DA SE NAPISAT OVIE NOTIFIKACII!!
        //send notification to the donor
        //$donor->notify(new AdminToDonorComment($listing_offer_id, $data['comment']));
        //send notification to the cso
        //$cso->notify(new AdminToCsoComment($listing_offer_id, $data['comment']));
        //send to the volunteer
        //$volunteer->notify(new AdminToVolunteerComment($listing_offer_id, $comment_text, $cso, $donor));

        return Comment::create([
            'listing_offer_id' => $listing_offer_id,
            'user_id' => Auth::user()->id,
            'sender_type' => Auth::user()->type(),
            'text' => $data['comment'],
        ]);
    }

    protected function handle_edit_comment(array $data, int $listing_offer_id)
    {
        $comment = $this->edit_comment($data, $listing_offer_id);
        return back()->with('status', "Коментарот е успешно променет.");
    }

    protected function edit_comment(array $data, int $listing_offer_id)
    {
        $comment = Comment::find($data['comment_id']);
        $comment->text = $data['edit_comment_text'];
        $comment->save();

        return $comment;
    }

    protected function handle_delete_comment(array $data, int $listing_offer_id)
    {
        $comment = $this->delete_comment($data, $listing_offer_id);
        return back()->with('status', "Коментарот е успешно избришан.");
    }

    protected function delete_comment(array $data, int $listing_offer_id)
    {
        $comment = Comment::find($data['comment_id']);
        $comment->status = 'deleted';
        $comment->save();

        return $comment;
    }

    protected function handle_edit_quantity(array $data, int $listing_offer_id)
    {
        $listing_offer = $this->update_quantity($data, $listing_offer_id);
        return back()->with('status', "Количината е променета успешно.");
    }

    protected function update_quantity(array $data, int $listing_offer_id)
    {
        $listing_offer = ListingOffer::find($listing_offer_id);
        $listing_offer->quantity = $data['edit_quantity'];
        $listing_offer->save();

        return $listing_offer;
    }

    protected function handle_update_volunteer(array $data, int $listing_offer_id)
    {
        $listing_offer = ListingOffer::find($listing_offer_id);
        if ($listing_offer->volunteer_id != $data['volunteer']) {
            $listing_offer = $this->update_volunteer($data, $listing_offer);
            return back()->with('status', "Подигнувачот е променет успешно.");
        } else {
            return back()->with('status', "Подигнувачот НЕ е променет.");
        }
    }

    protected function update_volunteer(array $data, ListingOffer $listing_offer)
    {
        $listing_offer->volunteer_id = $data['volunteer'];
        $listing_offer->save();

        return $listing_offer;
    }

    protected function handle_delete_listing_offer(array $data, int $listing_offer_id)
    {
        $listing_offer = ListingOffer::find($listing_offer_id);
        if ($listing_offer) {
            $listing_offer = $this->delete_listing_offer($data, $listing_offer);
            return redirect(route('admin.listings'))->with('status', "Прифатената донација е избришана.");
        } else {
            return back()->with('status', "Прифатената донација НЕ е избришана.");
        }
    }


    protected function delete_listing_offer(array $data, ListingOffer $listing_offer)
    {
        $listing_offer->offer_status = 'deleted';
        $listing_offer->save();

        return $listing_offer;
    }
}
