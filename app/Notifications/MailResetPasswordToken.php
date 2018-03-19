<?php
namespace FSR\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MailResetPasswordToken extends Notification
{
    use Queueable;

    public $token;

    /**
        * Create a new notification instance.
        *
        * @return void
        */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
        * Get the notification's delivery channels.
        *
        * @param	 mixed	 $notifiable
        * @return array
        */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
        * Get the mail representation of the notification.
        *
        * @param	 mixed	 $notifiable
        * @return \Illuminate\Notifications\Messages\MailMessage
        */
    public function toMail($notifiable)
    {
        return (new MailMessage)
              ->subject("Ресетирај ја лозинката")
              ->line("Долу се наоѓа линк за ресетирање на вашата лозинка.")
              ->action('Промени лозинка', url('password/reset', $this->token))
              ->line('Кликнете тука и продолжете да ја поддржувате целта.');
    }
}
