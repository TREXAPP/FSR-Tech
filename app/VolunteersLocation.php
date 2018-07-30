<?php

namespace FSR;

use Illuminate\Database\Eloquent\Model;

class VolunteersLocation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'volunteer_id',
        'location_id',
        'comment',
        'status'
    ];

    /**
     * Get the listings for this product.
     */
    public function volunteers()
    {
        return $this->hasMany('FSR\Volunteer');
    }
    /**
     * Get the listings for this product.
     */
    public function locations()
    {
        return $this->hasMany('FSR\Location');
    }
}
