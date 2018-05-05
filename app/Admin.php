<?php

namespace FSR;

use Illuminate\Notifications\Notifiable;
use FSR\Notifications\MailResetPasswordToken;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $type = "admin";

    public function type()
    {
        return $this->type;
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
        'profile_image_id',
        'master_admin'
      ];

    /**
   * Send a password reset email to the user
   */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MailResetPasswordToken($token));
    }
}
