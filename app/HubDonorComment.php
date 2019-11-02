<?php

namespace FSR;

use Illuminate\Database\Eloquent\Model;

class HubDonorComment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
          'reply_id',
          'hub_listing_offer_id',
          'user_id',
          'status',
          'sender_type',
          'text',
          'created_at',
          'updated_at',
      ];

    /**
     * Get the donor for this listing.
     */
    public function hub_listing_offer()
    {
        return $this->belongsTo('FSR\HubListingOffer');
    }
}
