<?php

namespace FSR\Notifications;

use FSR\File;
use FSR\ListingOffer;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CsoToVolunteerCancelDonation extends Notification
{
    use Queueable;

    private $listing_offer;
    private $cso;
    private $donor;

    /**
     * Create a new notification instance.
     * @param ListingOffer $listing_offer
     * @return void
     */
    public function __construct(ListingOffer $listing_offer, $cso, $donor)
    {
        $this->listing_offer = $listing_offer;
        $this->cso = $listing_offer->cso;
        $this->donor = $listing_offer->listing->donor;
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
        $message = (new MailMessage)
                  ->subject('Откажана донација!')
                  ->line('Ве известуваме дека донацијата за која бевте задолжени за подигнување е откажана. Подетални информации подолу:')
                  ->line('')
                  ->line('Податоци за донацијата:')
                  ->line('Производ: ' . $this->listing_offer->listing->product->name)
                  ->line('Количина: ' . $this->listing_offer->quantity . ' ' . $this->listing_offer->listing->quantity_type->description)
                  ->line('')
                  ->line('Податоци за донорот')
                  ->line('Име и презиме: ' . $this->donor->first_name . ' ' . $this->donor->last_name)
                  ->line('Организација: ' . $this->donor->organization->name)
                  ->line('')
                  ->line('Податоци за примателот')
                  ->line('Име и презиме: ' . $this->cso->first_name . ' ' . $this->cso->last_name)
                  ->line('Организација: ' . $this->cso->organization->name);

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
