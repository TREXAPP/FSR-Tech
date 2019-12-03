<?php

namespace FSR\Notifications;

use FSR\File;
use FSR\HubListingOffer;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class HubToDonorAcceptDonation extends Notification
{
    use Queueable;

    protected $hub_listing_offer;

    /**
     * Create a new notification instance.
     * @param HubListingOffer $hub_listing_offer
     * @return void
     */
    public function __construct(HubListingOffer $hub_listing_offer)
    {
        $this->hub_listing_offer = $hub_listing_offer;
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
        $message = (new MailMessage)->subject('[Сите Сити] Донацијата е прифатена')
                  ->line($this->hub_listing_offer->hub->first_name . ' ' . $this->hub_listing_offer->hub->last_name . ' - ' . $this->hub_listing_offer->hub->organization->name . ' ја прифати Вашата донација.')
                  ->line('Информации за донацијата:')
                  ->line('Прифатена количина: ' . $this->hub_listing_offer->quantity . ' (од ' . $this->hub_listing_offer->listing->quantity . ') ' . $this->hub_listing_offer->listing->quantity_type->description . ' ' .  $this->hub_listing_offer->listing->product->name)
                  ->line('<hr>')
                  ->line('Информации за хабот:')
                  ->line('Телефон: ' . $this->hub_listing_offer->hub->phone)
                  ->line('Адреса: ' . $this->hub_listing_offer->hub->address);

        $message->action('Кон донацијата', route('donor.single_hub_listing_offer', $this->hub_listing_offer->id));

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
