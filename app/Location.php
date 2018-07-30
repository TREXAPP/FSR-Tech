<?php

namespace FSR;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
          'name',
          'description',
          'status',
      ];

    /**
     * Get the csos for this location.
     */
    public function csos()
    {
        return $this->hasMany('FSR\Cso');
    }

    /**
     * Get the donors for this location.
     */
    public function donors()
    {
        return $this->hasMany('FSR\Donor');
    }
    /**
     * Get the volunteers that belong to the location.
     */
    public function volunteers()
    {
        return $this->belongsToMany('FSR\Volunteer', 'volunteers_locations')->withPivot('status');
    }
    /*
        protected $fillable = [
          'name', 'description',
      ];
      */
}
