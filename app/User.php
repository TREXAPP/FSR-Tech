<?php

namespace FSR;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
 * Get the cso that owns this user
 */
    public function cso()
    {
        return $this->hasOne('FSR\Cso');
    }

    /**
    * Get the donor that owns this user
    */
    public function donor()
    {
        return $this->hasOne('FSR\Donor');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
