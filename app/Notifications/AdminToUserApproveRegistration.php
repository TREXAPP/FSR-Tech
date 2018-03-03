<?php

namespace FSR\Notifications;

use FSR\File;
use FSR\ListingOffer;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AdminToUserApproveRegistration extends Notification
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
        $message = (new MailMessage)
                  ->subject('Вашиот профил е активиран!')
                  ->line('Би сакале да ве известиме дека вашиот профил за примател на храна на платформата за донирање на храна е активиран!');

        if ($this->user->email_confirmed) {
            $message->line('Кликнете подолу за да се логирате:')
                            ->action('Логирај се', route('home'));
        } else {
            $message->line('Вашиот емаил се уште не е активиран. Кликнете подолу за активација:')
                            ->action('Активирај емаил', $confirm_link);
        }
        return $message;
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
