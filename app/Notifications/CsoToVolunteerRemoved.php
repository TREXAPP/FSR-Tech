<?php

namespace FSR\Notifications;

use FSR\Organization;
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
                    ->subject('Отстранети сте од системот за донирање')
                    ->line('Ве известуваме дека сте отстранети како волонтер на ' . $this->organization->name)
                    ->line('Ако сметате дека сте отстранети по грешка, Ве молиме контактирајте ја организацијата директно.');
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