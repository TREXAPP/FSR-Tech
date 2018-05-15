<?php

namespace FSR\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AdminToVolunteerRemoved extends Notification
{
    use Queueable;

    private $organization;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($organization)
    {
        $this->organization = $organization;
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
        return (new MailMessage)
                   ->subject('Отстранети сте од платформата СитеСити')
                   ->line('Отстранети сте како доставувач на организацијата ' . $this->organization->name . ' од страна на администраторот.')
                   ->line('Ако е ова неточно, Ве молиме контактирајте го администраторот на <a href="mailto:' . config('app.master_admin') . '">' . config('app.master_admin') . '</a>');
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
