<?php

namespace FSR\Notifications;

use FSR\Organization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CsoToVolunteerRemoved extends Notification
{
    use Queueable;

    private $organization;

    /**
     * Create a new notification instance.
     *
     *@param FSR\Organization $organization
     * @return void
     */
    public function __construct(Organization $organization)
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
                    ->subject('[Сите Сити] Отстранети сте од платформата СитеСити')
                    ->line('Отстранети сте како доставувач на организацијата ' . $this->organization->name)
                    ->line('Ако е ова неточно, Ве молиме контактирајте ја организацијата на телефонскиот број ' . Auth::user()->phone . '.');
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
