<?php

namespace FSR\Notifications;

use FSR\Custom\CarbonFix;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserToAdminEditProfile extends Notification implements ShouldQueue
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
        if ($this->user->type() == 'cso') {
            $type = "примател";
        } else {
            $type = "донатор";
        }
        $message = (new MailMessage)->subject('Променет е профилот на ' . $type . 'от.')
                    ->line('Направени се следниве измени:')
                    ->line('Тип: ' . $type)
                    ->line('Име: ' . $this->user->first_name)
                    ->line('Презиме: ' . $this->user->last_name)
                    ->line('Емаил: ' . $this->user->email)
                    ->line('Организација: ' . $this->user->organization->name)
                    ->line('Адреса: ' . $this->user->address)
                    ->line('Телефон: ' . $this->user->phone)
                    ->line('Локација: ' . $this->user->location->name);
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
