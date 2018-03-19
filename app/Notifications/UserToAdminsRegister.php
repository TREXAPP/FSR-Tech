<?php

namespace FSR\Notifications;

use FSR\Volunteer;
use FSR\Custom\CarbonFix;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserToAdminsRegister extends Notification implements ShouldQueue
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
        $approve_link = route('admin.approve_users');
        return (new MailMessage)
                    ->subject('Нов член - Потребно е одобрување')
                    ->line('Ве молиме потврдете дека долу наведените информации за најнов член на платформата е точна:')
                    ->line('Тип на корисник: ' . $this->user->type())
                    ->line('Име: ' . $this->user->first_name)
                    ->line('Презиме: ' . $this->user->last_name)
                    ->line('Организација: ' . $this->user->organization->name)
                    ->line('Емаил: ' . $this->user->email)
                    ->line('Телефон: ' . $this->user->phone)
                    ->line('Адреса: ' . $this->user->address)
                    ->line('Локација: ' . $this->user->location->name)
                    ->line('Кликнете на линкот за да го одобрите овој член.')
                    ->action('Одобри/Одбиј', $approve_link);
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
