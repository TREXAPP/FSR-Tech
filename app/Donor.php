<?php

namespace FSR;

use Illuminate\Notifications\Notifiable;
use FSR\Notifications\MailResetPasswordToken;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Donor extends Authenticatable
{
    use Notifiable;

    protected $type = "donor";

    public function type()
    {
        return $this->type;
    }

    /**
     * Get the location for this donor.
     */
    public function location()
    {
        return $this->belongsTo('FSR\Location');
    }

    /**
     * Get the organization for this donor.
     */
    public function organization()
    {
        return $this->belongsTo('FSR\Organization');
    }

    /**
     * Get the listings for this donor.
     */
    public function listings()
    {
        return $this->hasMany('FSR\Listing');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'email',
      'password',
      'first_name',
      'last_name',
      'phone',
      'address',
      'profile_image_id',
      'organization_id',
      'location_id',
      'notifications',
      'status',
      'email_token',
      'email_confirmed'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
 * Send a password reset email to the user
 */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MailResetPasswordToken($token));
    }
}
