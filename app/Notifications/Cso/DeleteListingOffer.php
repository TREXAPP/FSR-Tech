<?php

namespace FSR\Notifications\Cso;

use FSR\File;
use FSR\ListingOffer;
use FSR\Cso;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DeleteListingOffer extends Notification
{
    use Queueable;

    private $listing_offer;

    /**
     * Create a new notification instance.
     * @param DeleteListingOffer $listing_offer
     * @return void
     */
    public function __construct(ListingOffer $listing_offer)
    {
        $this->listing_offer = $listing_offer;
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
                  ->subject('Прифатената донација е откажана!')
                  ->line('Прифатените ' . $this->listing_offer->quantity . ' ' . $this->listing_offer->listing->quantity_type->description .
                          ' ' . $this->listing_offer->listing->product->name .
                          ' од ' . $this->listing_offer->cso->first_name . ' ' . $this->listing_offer->cso->last_name .
                          ' - ' . $this->listing_offer->cso->organization->name . ' се откажани!')
                  ->action('Кон донацијата', route('donor.my_active_listings'));
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
