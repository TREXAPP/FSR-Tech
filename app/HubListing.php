<?php

namespace FSR;

use Illuminate\Database\Eloquent\Model;

class HubListing extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
          'hub_id',
          'product_id',
          'description',
          'quantity',
          'quantity_type_id',
          'date_listed',
          'date_expires',
          'pickup_time_from',
          'pickup_time_to',
          'status',
          'image_id',
          'sell_by_date',
      ];

    /**
     * Get the listing_offers for this listing.
     */
    public function listing_offers()
    {
        return $this->hasMany('FSR\ListingOffer');
    }


    /**
     * Get the donor for this listing.
     */
    public function hub()
    {
        return $this->belongsTo('FSR\Hub');
    }

    /**
     * Get the product for this listing.
     */
    public function product()
    {
        return $this->belongsTo('FSR\Product');
    }

    /**
     * Get the quantity_type for this listing.
     */
    public function quantity_type()
    {
        return $this->belongsTo('FSR\QuantityType');
    }
}
