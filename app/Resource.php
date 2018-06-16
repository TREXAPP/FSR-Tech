<?php

namespace FSR;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    'name',
    'user_types',
    'description',
    'comment',
  ];
}
