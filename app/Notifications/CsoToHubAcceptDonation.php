<?php

namespace FSR\Notifications;

use FSR\File;
use FSR\ListingOffer;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CsoToHubAcceptDonation extends Notification
{
    use Queueable;

    protected $listing_offer;

    /**
     * Create a new notification instance.
     * @param ListingOffer $listing_offer
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
        $message = (new MailMessage)->subject('[Сите Сити] Донацијата е прифатена')
                  ->line($this->listing_offer->cso->first_name . ' ' . $this->listing_offer->cso->last_name . ' - ' . $this->listing_offer->cso->organization->name . ' ја прифати Вашата донација.')
                  ->line('Информации за донацијата:')
                  ->line('Прифатена количина: ' . $this->listing_offer->quantity . ' (од ' . $this->listing_offer->hub_listing->quantity . ') ' . $this->listing_offer->hub_listing->quantity_type->description . ' ' .  $this->listing_offer->hub_listing->product->name)
                  ->line('<hr>');

        if ($this->listing_offer->delivered_by_hub) {
            $message->line('⚠️ <b>Примателот одбра да му се достави донацијата!</b>');
        } else {
             $message->line('Лице за подигнување: ' . $this->listing_offer->volunteer->first_name . ' ' . $this->listing_offer->volunteer->last_name)
                     ->line('Телефон: ' . $this->listing_offer->volunteer->phone);

            if ($this->listing_offer->volunteer->image_id) {
                $message->line('Слика:');
                $message->line('<img style="width: 150px; height: auto;" src="' . url('storage' . config('app.upload_path') . '/' . File::find($this->listing_offer->volunteer->image_id)->filename) . '" alt="Доставувач" />');
            }
        }
        if ($this->listing_offer->cso->organization->address) {
        $message->line('<hr>')
                ->line('<br>')
                ->line('Адреса на организацијата: ' . $this->listing_offer->cso->organization->address);
        }

        $message->action('Кон донацијата', route('hub.single_listing_offer', $this->listing_offer->id));

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
