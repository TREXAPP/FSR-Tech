<?php

namespace FSR;

use Illuminate\Database\Eloquent\Model;

class QuantityType extends Model
{
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    'name', 'description', 'portion_size'
];

    /**
     * Get the listings for this quantity_type.
     */
    public function listings()
    {
        return $this->hasMany('FSR\Listing');
    }
}
