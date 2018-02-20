<?php

namespace FSR;

use Illuminate\Database\Eloquent\Model;

class DonorType extends Model
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
     * Get the dononrs for this donor_type.
     */
    public function organizations()
    {
        return $this->hasMany('FSR\Organization');
    }
}
