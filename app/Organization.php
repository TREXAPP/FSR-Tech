<?php

namespace FSR;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    public $timestamps = false;

    /**
     * Get the csos for this organization.
     */
    public function csos()
    {
        return $this->hasMany('FSR\Cso');
    }

    /**
     * Get the donors for this organization.
     */
    public function donors()
    {
        return $this->hasMany('FSR\Donor');
    }

    /**
     * Get the donors for this organization.
     */
    public function volunteers()
    {
        return $this->hasMany('FSR\Volunteer');
    }

    protected $fillable = [
      'name', 'description', 'type'
  ];
}
