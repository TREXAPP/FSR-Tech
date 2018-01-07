<?php

namespace FSR;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    public $timestamps = false;

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
    /*
        protected $fillable = [
          'name', 'description',
      ];
      */
}
