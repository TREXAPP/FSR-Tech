<?php

namespace FSR;

use Illuminate\Database\Eloquent\Model;

class VolunteerAvailability extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'volunteer_id',
        'timeframe_id',
        'is_available',
        'comment',
        'status',
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
    public function timeframes()
    {
        return $this->hasMany('FSR\Timeframe');
    }
}
