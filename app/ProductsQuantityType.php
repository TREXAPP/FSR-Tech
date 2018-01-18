<?php

namespace FSR;

use Illuminate\Database\Eloquent\Model;

class ProductsQuantityType extends Model
{
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
          'product_id',
          'quantity_type_id',
          'portion_size',
          'default',
      ];

    /**
     * Get the listings for this product.
     */
    public function quantity_types()
    {
        return $this->hasMany('FSR\QuantityType');
    }
    /**
     * Get the listings for this product.
     */
    public function products()
    {
        return $this->hasMany('FSR\Product');
    }
}
