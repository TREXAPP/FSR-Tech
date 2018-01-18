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
    'name', 'description'
];

    /**
     * Get the listings for this quantity_type.
     */
    public function listings()
    {
        return $this->hasMany('FSR\Listing');
    }


    /**
     * Get the products that belong to the quantity_type.
     */
    public function products()
    {
        return $this->belongsToMany('FSR\Product', 'products_quantity_types')->withPivot('default', 'portion_size');
    }
}
