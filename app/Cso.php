<?php

namespace FSR;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Cso extends Authenticatable
{
    use Notifiable;

    protected $type = "cso";

    public function type()
    {
        return $this->type;
    }

    /**
     * Get the location for this cso.
     */
    public function location()
    {
        return $this->belongsTo('FSR\Location');
    }

    /**
     * Get the organization for this cso.
     */
    public function organization()
    {
        return $this->belongsTo('FSR\Organization');
    }

    /**
     * Get the listing_offers for this cso.
     */
    public function listing_offers()
    {
        return $this->hasMany('FSR\ListingOffer');
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'email',
      'password',
      'first_name',
      'last_name',
      'phone',
      'address',
      'profile_image_id',
      'organization_id',
      'location_id',
      'notifications',
      'status',
      'email_token',
      'email_confirmed'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];
}
