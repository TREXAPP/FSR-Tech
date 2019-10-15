<?php

namespace FSR;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
          'id',
          'name',
          'description',
      ];

    /**
     * Get the locations for this region.
     */
    public function locations()
    {
        return $this->hasMany('FSR\Location');
    }

    /*
        protected $fillable = [
          'name', 'description',
      ];
      */
}
