<?php

namespace FSR;

use Illuminate\Database\Eloquent\Model;

class DonorType extends Model
{
    public $timestamps = false;

    /**
     * Get the dononrs for this donor_type.
     */
    public function donors()
    {
        return $this->hasMany('FSR\Donor');
    }
}
