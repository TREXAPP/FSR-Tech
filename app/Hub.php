<?php

namespace FSR;

use Illuminate\Notifications\Notifiable;
use FSR\Notifications\MailResetPasswordToken;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Hub extends Authenticatable
{
    use Notifiable;

    protected $type = "hub";

    public function type()
    {
        return $this->type;
    }

    /**
     * Get the region for this hub.
     */
    public function region()
    {
        return $this->belongsTo('FSR\Region');
    }

    /**
     * Get the organization for this cso.
     */
    public function organization()
    {
        return $this->belongsTo('FSR\Organization');
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
      'region_id',
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
