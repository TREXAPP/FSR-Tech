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
}
