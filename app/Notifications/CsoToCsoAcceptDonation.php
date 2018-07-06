<?php

namespace FSR\Notifications;

use FSR\File;
use FSR\ListingOffer;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use FSR\Custom\CarbonFix;

class CsoToCsoAcceptDonation extends Notification
{
    use Queueable;

    protected $listing_offer;
    protected $donor;

    /**
     * Create a new notification instance.
     * @param ListingOffer $listing_offer
     * @return void
     */
    public function __construct(ListingOffer $listing_offer)
    {
        $this->listing_offer = $listing_offer;
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
        $message = (new MailMessage)->subject('[Сите Сити] Донацијата е прифатена')
                  ->line('Имате прифатено нова донација!')
                  ->line('Информации за донацијата:')
                  ->line('Производ: ' . $this->listing_offer->listing->product->name)
                  ->line('Количина: ' . $this->listing_offer->quantity . ' ' . $this->listing_offer->listing->quantity_type->description)
                  ->line('Време за подигање од ' . CarbonFix::parse($this->listing_offer->listing->pickup_time_from)->format('H:i') . ' до ' . CarbonFix::parse($this->listing_offer->listing->pickup_time_to)->format('H:i'))
                  ->line('<hr>')
                  ->line('Податоци за донаторот')
                  ->line('Име и презиме: ' . $this->donor->first_name . ' ' . $this->donor->last_name)
                  ->line('Организација: ' . $this->donor->organization->name)
                  ->line('Телефон: ' . $this->donor->phone)
                  ->line('Емаил: ' . $this->donor->email)
                  ->line('Адреса: ' . $this->donor->address . ' - ' . $this->donor->location->name)
                  ->line('<hr>')
                  ->line('Лице за подигнување: ' . $this->listing_offer->volunteer->first_name . ' ' . $this->listing_offer->volunteer->last_name)
                  ->line('Телефон: ' . $this->listing_offer->volunteer->phone);
        if ($this->listing_offer->volunteer->image_id) {
            $message->line('Слика:');
            $message->line('<img style="width: 150px; height: auto;" src="' . url('storage' . config('app.upload_path') . '/' . File::find($this->listing_offer->volunteer->image_id)->filename) . '" alt="Доставувач" />');
        }
        $message->line('<br>')
                ->action('Кон донацијата', url('/cso/accepted_listings/' . $this->listing_offer->id));

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
