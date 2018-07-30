<?php

namespace FSR;

use Illuminate\Database\Eloquent\Model;

class TransportType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    'name', 'quantity', 'comment', 'status'
];

    /**
     * Get the volunteers that belong to the transport_type.
     */
    public function volunteers()
    {
        return $this->belongsToMany('FSR\Volunteer', 'volunteers_transport_types')->withPivot('status');
    }
}
