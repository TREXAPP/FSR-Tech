<?php

namespace FSR;

use Illuminate\Database\Eloquent\Model;

class Timeframe extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    'day', 'hours_from', 'hours_to', 'comment', 'status'
];
    /**
     * Get the volunteers that belong to the timeframe.
     */
    public function volunteers()
    {
        return $this->belongsToMany('FSR\Volunteer', 'volunteer_availabilities')->withPivot('is_available', 'status');
    }
}
