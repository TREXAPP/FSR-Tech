<?php

namespace FSR\Notifications;

use FSR\Volunteer;
use FSR\Custom\CarbonFix;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserRegistrationSuccess extends Notification implements ShouldQueue
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
                    ->subject('[Сите Сити] Ви благодариме за регистрацијата')
                    ->line('Ви благодариме за регистрацијата за да бидете дел од платформата за донирање на вишок храна.')
                    ->line('Кликнете подолу за да го потврдите Вашиот емаил:')
                    ->action('Потврди емаил', $confirm_link)
                    ->line('Во моментов ја проверуваме вашата регистрација. Кога ќе биде одобрена ќе бидете известени преку вашата е-пошта.');
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
