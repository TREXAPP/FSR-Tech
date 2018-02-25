<?php

namespace FSR\Notifications;

use FSR\Listing;
use FSR\Custom\CarbonFix;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DonorToCsosNewDonation extends Notification implements ShouldQueue
{
    use Queueable;


    protected $listing;

    /**
     * Create a new notification instance.
     *
     * @param Listing $listing
     * @return void
     */
    public function __construct(Listing $listing)
    {
        $this->listing = $listing;
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
                    ->subject('Нова донација')
                    ->line('Додадена е нова донација од ' . $this->listing->donor->first_name . ' ' . $this->listing->donor->last_name . ' - ' . $this->listing->donor->organization->name . '.')
                    ->line('Донацијата ќе биде активна ' . CarbonFix::parse($this->listing->date_expires)->diffForHumans() . '.')
                    ->action('Прифати ја донацијата', url('/cso/active_listings/' . $this->listing->id));
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
