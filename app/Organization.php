<?php

namespace FSR;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{

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

    /**
     * Get the donor_type for this donor.
     */
    public function donor_type()
    {
        return $this->belongsTo('FSR\DonorType');
    }

    protected $fillable = [
      'name',
      'description',
      'type',
      'address',
      'working_hours_from',
      'working_hours_to',
      'image_id',
      'status',
      'donor_type_id',
      'created_at',
      'updated_at',

  ];
}
