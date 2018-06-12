<?php

namespace FSR;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    'event',
    'user_id',
    'user_type',
    'comment',
  ];

    /**
     * Get the cso for this log.
     */
    public function cso()
    {
        return $this->belongsTo('FSR\Cso');
    }

    /**
     * Get the donor for this log.
     */
    public function donor()
    {
        return $this->belongsTo('FSR\Donor');
    }
}
