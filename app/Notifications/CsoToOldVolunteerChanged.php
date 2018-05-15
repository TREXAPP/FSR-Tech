<?php

namespace FSR\Notifications;

use FSR\File;
use FSR\ListingOffer;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CsoToOldVolunteerChanged extends Notification
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
          ->subject('Имате промени во донацијата за подигнување')
          ->line($this->listing_offer->cso->first_name . ' ' . $this->listing_offer->cso->last_name . ' - ' . $this->listing_offer->cso->organization->name .
                  ' го смени доставувачот на донацијата која што требаше да ја подигнете.')
          ->line('<hr>')
          ->line('Информации за донацијата: ')
          ->line('Производ: ' . $this->listing_offer->listing->product->name)
          ->line('Количина: ' . $this->listing_offer->quantity . ' ' . $this->listing_offer->listing->quantity_type->description)
          ->line('Донатор: ' . $this->donor->first_name . ' ' . $this->donor->last_name . ' - ' . $this->donor->organization->name)
          ->line('<hr>')
          ->line('Ви благодариме што го поддржувате нашиот труд да го намалиме отпадот од храна и недостаток на храна во Македонија! ');
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
