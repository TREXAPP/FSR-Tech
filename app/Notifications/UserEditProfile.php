<?php

namespace FSR\Notifications;

use FSR\Custom\CarbonFix;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserEditProfile extends Notification implements ShouldQueue
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
        $message = (new MailMessage)->subject('[Сите Сити] Направени се промени на профилот.')
                    ->line('Приметивме дека направивте измени на вашиот профил.')
                    ->line('Вашите моментални податоци се:')
                    ->line('Име: ' . $this->user->first_name)
                    ->line('Презиме: ' . $this->user->last_name)
                    ->line('Емаил: ' . $this->user->email)
                    ->line('Организација: ' . $this->user->organization->name)
                    ->line('Адреса: ' . $this->user->address)
                    ->line('Телефон: ' . $this->user->phone)
                    ->line('Локација: ' . $this->user->location->name)
                    ->line(' Ако вашите измени не се точни Ве молиме кликнете на линкот за да го измените вашиот профил.');

        if ($this->user->type() == 'cso') {
            $message->action('Измени профил', url('/cso/edit_profile'));
        } else {
            $message->action('Измени профил', url('/donor/edit_profile'));
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
