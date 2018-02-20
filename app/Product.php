<?php

namespace FSR;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
          'name',
          'description',
          'food_type_id',
          'status',
      ];

    /**
     * Get the listings for this product.
     */
    public function listings()
    {
        return $this->hasMany('FSR\Listing');
    }

    /**
     * Get the food_type for this listing.
     */
    public function food_type()
    {
        return $this->belongsTo('FSR\FoodType');
    }

    /**
     * Get the quantity_types that belong to the product.
     */
    public function quantity_types()
    {
        return $this->belongsToMany('FSR\QuantityType', 'products_quantity_types')->withPivot('default', 'portion_size');
    }
}
