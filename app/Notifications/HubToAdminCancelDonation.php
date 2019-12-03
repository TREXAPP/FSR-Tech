<?php

namespace FSR\Notifications;

use FSR\File;
use FSR\Admin;
use FSR\HubListingOffer;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class HubToAdminCancelDonation extends Notification
{
    use Queueable;

    private $hub_listing_offer;
    private $hub;
    private $donor;

    /**
     * Create a new notification instance.
     * @param HubListingOffer $listing_offer
     * @return void
     */
    public function __construct(HubListingOffer $hub_listing_offer, $hub, $donor)
    {
        $this->hub_listing_offer = $hub_listing_offer;
        $this->hub = $hub_listing_offer->hub;
        $this->donor = $hub_listing_offer->listing->donor;
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
                  ->subject('[Сите Сити] Подигнувањето на донацијата е откажано.')
                  ->line($this->hub->first_name . ' ' . $this->hub->last_name . ' - ' . $this->hub->organization->name . ' го откажа прифаќањето на донацијата.')
                  ->line('<br>')
                  ->line('Информации за донацијата:')
                  ->line('Производ: ' . $this->hub_listing_offer->listing->product->name)
                  ->line('Откажана количина: ' . $this->hub_listing_offer->quantity . ' ' . $this->hub_listing_offer->listing->quantity_type->description)
                  ->line('<hr>')
                  ->line('Податоци за хабот')
                  ->line('Име и презиме: ' . $this->hub->first_name . ' ' . $this->hub->last_name)
                  ->line('Организација: ' . $this->hub->organization->name)
                  ->line('<hr>')
                  ->line('Податоци за донаторот')
                  ->line('Име и презиме: ' . $this->donor->first_name . ' ' . $this->donor->last_name)
                  ->line('Организација: ' . $this->donor->organization->name);

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
