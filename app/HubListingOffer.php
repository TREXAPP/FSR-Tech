<?php

namespace FSR;

use Illuminate\Database\Eloquent\Model;

class HubListingOffer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    'hub_id',
    'listing_id',
    'status',
    'quantity',
  ];

    /**
     * Get the comments for this listing_offer.
     */
    public function comments()
    {
        return $this->hasMany('FSR\HubDonorComment');
    }

    /**
     * Get the hub for this listing_offer.
     */
    public function hub()
    {
        return $this->belongsTo('FSR\Hub');
    }

    /**
     * Get the listing for this listing_offer.
     */
    public function listing()
    {
        return $this->belongsTo('FSR\Listing');
    }

}
