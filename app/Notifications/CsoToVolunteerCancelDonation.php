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
                  ->subject('Подигнување на донацијата е откажано.')
                  ->line($this->cso->first_name . ' ' . $this->cso->last_name . ' - ' . $this->cso->organization->name . ' нема потреба од подигнување на донацијата')
                  ->line('<br>')
                  ->line('Информации за донацијата:')
                  ->line('Производ: ' . $this->listing_offer->listing->product->name)
                  ->line('Откажана количина: ' . $this->listing_offer->quantity . ' ' . $this->listing_offer->listing->quantity_type->description)
                  ->line('<br>')
                  ->line('Податоци за донаторот')
                  ->line('Име и презиме: ' . $this->donor->first_name . ' ' . $this->donor->last_name)
                  ->line('Организација: ' . $this->donor->organization->name)
                  ->line('<hr>')
                  ->line('Ви благодариме што го подржувате нашиот труд да го намалиме отпадот од храна и недостаток на храна во Македонија!');

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
