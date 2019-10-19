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
     * Get the hubs for this organization.
     */
    public function hubs()
    {
        return $this->hasMany('FSR\Hub');
    }

    public function donor_logs()
    {
        return $this->hasManyThrough('FSR\Log', 'FSR\Donor', 'organization_id', 'user_id', 'id', 'id');
    }

    public function cso_logs()
    {
        return $this->hasManyThrough('FSR\Log', 'FSR\Cso', 'organization_id', 'user_id', 'id', 'id');
    }

    public function hub_logs()
    {
        return $this->hasManyThrough('FSR\Log', 'FSR\Hub', 'organization_id', 'user_id', 'id', 'id');
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

    /**
     * Get the region for this donor.
     */
    public function region()
    {
        return $this->belongsTo('FSR\Region');
    }

    /**
     * Get the volunteers that belong to the organization.
     */
    public function free_volunteers()
    {
        return $this->belongsToMany('FSR\Volunteer', 'volunteers_organizations')->withPivot('type', 'status');
    }

    protected $fillable = [
      'id',
      'name',
      'description',
      'type',
      'address',
      'working_hours_from',
      'working_hours_to',
      'image_id',
      'status',
      'donor_type_id',
      'region_id',
      'created_at',
      'updated_at',

  ];
}
