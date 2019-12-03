<?php

namespace FSR\Notifications;

use FSR\File;
use FSR\HubListingOffer;
use FSR\Hub;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class HubToDonorCancelDonation extends Notification
{
    use Queueable;

    private $hub_listing_offer;

    /**
     * Create a new notification instance.
     * @param HubToDonorCancelDonation $listing_offer
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
        $message = (new MailMessage)
          ->subject('[Сите Сити] Подигнување на донацијата е откажано.')
          ->line($this->hub_listing_offer->hub->first_name . ' ' . $this->hub_listing_offer->hub->last_name .
              ' - ' . $this->hub_listing_offer->hub->organization->name . ' не е во можност да ја прифати вашата донација.')
          ->line('Информации за донацијата:')
          ->line('Производ: ' . $this->hub_listing_offer->listing->product->name)
          ->line('Откажана количина: ' . $this->hub_listing_offer->quantity . ' ' . $this->hub_listing_offer->listing->quantity_type->description)
          ->line('Донацијата ќе ја направиме достапна за хабовите на платформата.')
          ->line('<br>')
          ->line('Ви благодариме што го поддржувате нашиот труд да ја го намалиме прехрамбениот отпад и недостаток на храната во Македонија!')
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
