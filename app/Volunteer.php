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
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];
}
