<?php

namespace FSR;

use Illuminate\Database\Eloquent\Model;

class ListingMsg extends Model
{
    /**
     * Get the listing_offer for this listing_msg.
     */
    public function listing_offer()
    {
        return $this->belongsTo('FSR\ListingOffer');
    }
}
