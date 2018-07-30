<?php

namespace FSR;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Volunteer extends Authenticatable
{
    use Notifiable;

    protected $type = "volunteer";

    public function type()
    {
        return 'volunteer';
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'first_name',
        'last_name',
        'phone',
        'image_id',
        'organization_id',
        'added_by_user_id',
        'status',
        'is_user',
        'type',
        'address',
      ];
    /**
     * Get the location for this cso.
     */
    public function organization()
    {
        return $this->belongsTo('FSR\Organization');
    }


    /**
     * Get the listing_offers for this volunteer.
     */
    public function listing_offers()
    {
        return $this->hasMany('FSR\ListingOffer');
    }

    /**
     * Get the locations that belong to the volunteer.
     */
    public function locations()
    {
        return $this->belongsToMany('FSR\Location', 'volunteers_locations')->withPivot('status');
    }

    /**
     * Get the organizations that belong to the volunteer.
     */
    public function free_organizations()
    {
        return $this->belongsToMany('FSR\Organization', 'volunteers_organizations')->withPivot('type', 'status');
    }

    /**
     * Get the transport_types that belong to the volunteer.
     */
    public function transport_types()
    {
        return $this->belongsToMany('FSR\TransportType', 'volunteers_transport_types')->withPivot('status');
    }

    /**
     * Get the transport_types that belong to the volunteer.
     */
    public function timeframes()
    {
        return $this->belongsToMany('FSR\Timeframe', 'volunteer_availabilities')->withPivot('is_available', 'status');
    }




    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];
}
