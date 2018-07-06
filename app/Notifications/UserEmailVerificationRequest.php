<?php

namespace FSR\Notifications;

use FSR\Volunteer;
use FSR\Custom\CarbonFix;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserEmailVerificationRequest extends Notification implements ShouldQueue
{
    use Queueable;

    private $user;

    /**
     * Create a new notification instance.
     * @param object $user
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $confirm_link = route('email.confirm', $this->user->email_token);
        return (new MailMessage)
                    ->subject('[Сите Сити] Ве молиме потврдете ја вашата е-мајл адреса')
                    ->line('Добивте одобрување за да ја користите платформата. Ве молиме кликнете на линкот за да го потврдите вашиот профил како би можеле да ја користите платформата.')
                    ->action('Потврди емаил', $confirm_link);
        // ->action('Види ги промените', url('/cso/profile'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
