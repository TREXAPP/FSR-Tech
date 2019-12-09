<?php

namespace FSR\Http\Controllers\Admin;

use FSR;
use FSR\Hub;
use FSR\File;
use FSR\Listing;
use FSR\HubListingOffer;
use FSR\HubDonorComment;
use FSR\Organization;
use FSR\Custom\Methods;
use FSR\Notifications\AdminToDonorComment;
use FSR\Notifications\AdminToHubDonorComment;

use FSR\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;

class HubListingOfferController extends Controller
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
     * @param  int  $hub_listing_offer_id
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $hub_listing_offer_id)
    {

        $hub_listing_offer = HubListingOffer::find($hub_listing_offer_id);
        $total_accepted_quantity = $hub_listing_offer->listing->hub_listing_offers->where('status','active')->sum('quantity');
        $max_quantity = $hub_listing_offer->listing->quantity - $total_accepted_quantity + $hub_listing_offer->quantity;

        $selected_filter = $this->get_selected_filter($hub_listing_offer);
        if (!$hub_listing_offer) {
            return redirect(route('admin.donor_listings'));
        } else {
            $comments = HubDonorComment::where('hub_listing_offer_id', $hub_listing_offer_id)
                            ->where('status', 'active')
                            ->orderBy('created_at', 'ASC')->get();
            return view('admin.hub_accepted_listing')->with([
        'hub_listing_offer' => $hub_listing_offer,
        'comments' => $comments,
        'selected_filter' => $selected_filter,
        'max_quantity' => $max_quantity,
      ]);
        }
    }


    /**
     * Handle post request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle_post(Request $request, $hub_listing_offer_id)
    {
        $data = $request->all();
        if (!empty($data['post-type'])) {
            switch ($data['post-type']) {
                case 'edit_quantity':
                  return $this->handle_edit_quantity($data, $hub_listing_offer_id);
                case 'delete_listing_offer':
                  return $this->handle_delete_listing_offer($data, $hub_listing_offer_id);
                case 'delete_comment':
                  return $this->handle_delete_comment($data, $hub_listing_offer_id);
                case 'edit_comment':
                  return $this->handle_edit_comment($data, $hub_listing_offer_id);
                case 'new_comment':
                  return $this->handle_insert_comment($data, $hub_listing_offer_id);
                default:
                  return $this->index(null, $hub_listing_offer_id);
                break;
              }
        }
    }

    protected function handle_insert_comment(array $data, int $hub_listing_offer_id)
    {
        $hub_listing_offer = $this->create_comment($data, $hub_listing_offer_id);
        return back()->with(['status' => 'Коментарот е внесен!']);
    }

    protected function create_comment(array $data, int $hub_listing_offer_id)
    {
        $hub_listing_offer = HubListingOffer::find($hub_listing_offer_id);
        $donor = $hub_listing_offer->listing->donor;
        $comment_text = $data['comment'];
        $hub = $hub_listing_offer->hub;
        $other_comments = HubDonorComment::where('status', 'active')->where('hub_listing_offer_id', $hub_listing_offer_id)->get();

        $comment = HubDonorComment::create([
            'hub_listing_offer_id' => $hub_listing_offer_id,
            'user_id' => Auth::user()->id,
            'sender_type' => Auth::user()->type(),
            'text' => $data['comment'],
        ]);

        // //send notification to the donor
        $donor->notify(new AdminToDonorComment($hub_listing_offer, $data['comment'], $other_comments, Auth::user()));
        // //send notification to the hub
        $hub->notify(new AdminToHubDonorComment($hub_listing_offer, $data['comment'], $other_comments, Auth::user()));

        return $comment;

    }

    protected function handle_edit_comment(array $data, int $hub_listing_offer_id)
    {
        $comment = $this->edit_comment($data, $hub_listing_offer_id);
        return back()->with('status', "Коментарот е успешно променет.");
    }

    protected function edit_comment(array $data, int $hub_listing_offer_id)
    {
        $comment = HubDonorComment::find($data['comment_id']);
        $comment->text = $data['edit_comment_text'];
        $comment->save();

        return $comment;
    }

    protected function handle_delete_comment(array $data, int $hub_listing_offer_id)
    {
        $comment = $this->delete_comment($data, $hub_listing_offer_id);
        return back()->with('status', "Коментарот е успешно избришан.");
    }

    protected function delete_comment(array $data, int $hub_listing_offer_id)
    {
        $comment = HubDonorComment::find($data['comment_id']);
        $comment->status = 'deleted';
        $comment->save();

        return $comment;
    }

    protected function handle_edit_quantity(array $data, int $hub_listing_offer_id)
    {
        $hub_listing_offer = $this->update_quantity($data, $hub_listing_offer_id);
        return back()->with('status', "Количината е променета успешно.");
    }

    protected function update_quantity(array $data, int $hub_listing_offer_id)
    {
        $hub_listing_offer = HubListingOffer::find($hub_listing_offer_id);
        $hub_listing_offer->quantity = $data['edit_quantity'];
        $hub_listing_offer->save();

        return $hub_listing_offer;
    }

    protected function handle_delete_listing_offer(array $data, int $hub_listing_offer_id)
    {
        $hub_listing_offer = HubListingOffer::find($hub_listing_offer_id);
        if ($hub_listing_offer) {
            $hub_listing_offer = $this->delete_listing_offer($data, $hub_listing_offer);
            return redirect(route('admin.donor_listings'))->with('status', "Прифатената донација е избришана.");
        } else {
            return back()->with('status', "Прифатената донација НЕ е избришана.");
        }
    }


    protected function delete_listing_offer(array $data, HubListingOffer $hub_listing_offer)
    {
        $hub_listing_offer->status = 'deleted';
        $hub_listing_offer->save();

        return $hub_listing_offer;
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
