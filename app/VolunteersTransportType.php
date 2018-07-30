<?php

namespace FSR;

use Illuminate\Database\Eloquent\Model;

class VolunteersTransportType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'volunteer_id',
        'transport_type_id',
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
    public function transport_types()
    {
        return $this->hasMany('FSR\TransportType');
    }
}
