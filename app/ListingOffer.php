<?php

namespace FSR;

use Illuminate\Database\Eloquent\Model;

class ListingOffer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    'cso_id',
    'hub_listing_id',
    'offer_status',
    'quantity',
    'volunteer_id',
    'delivered_by_hub',
  ];
    /**
     * Get the listing_msgs for this listing_offer.
     */
    public function listing_msgs()
    {
        return $this->hasMany('FSR\ListingMsg');
    }

    /**
     * Get the comments for this listing_offer.
     */
    public function comments()
    {
        return $this->hasMany('FSR\CsoHubComment');
    }

    /**
     * Get the cso for this listing_offer.
     */
    public function cso()
    {
        return $this->belongsTo('FSR\Cso');
    }

    /**
     * Get the listing for this listing_offer.
     */
    public function hub_listing()
    {
        return $this->belongsTo('FSR\HubListing');
    }

    /**
     * Get the location for this cso.
     */
    public function volunteer()
    {
        return $this->belongsTo('FSR\Volunteer');
    }
}
