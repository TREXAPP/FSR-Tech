<?php

namespace FSR;

use Illuminate\Database\Eloquent\Model;

class VolunteersOrganization extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'volunteer_id',
        'organization_id',
        'type',
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
    public function organizations()
    {
        return $this->hasMany('FSR\Organization');
    }
}
