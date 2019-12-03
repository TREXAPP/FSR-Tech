<?php

namespace FSR\Notifications;

use FSR\File;
use FSR\Volunteer;
use FSR\ListingOffer;
use FSR\Custom\CarbonFix;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CsoToHubVolunteerChanged extends Notification implements ShouldQueue
{
    use Queueable;

    private $listing_offer;
    private $volunteer;

    /**
     * Create a new notification instance.
     * @param int $listing_offer_id
     * @param Volunteer $volunteer
     * @return void
     */
    public function __construct(ListingOffer $listing_offer, Volunteer $volunteer)
    {
        $this->listing_offer = $listing_offer;
        $this->volunteer = $volunteer;
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
                    ->subject('[Сите Сити] Имате промени во вашата донација')
                    ->line($this->listing_offer->cso->first_name . ' ' . $this->listing_offer->cso->last_name . ' - ' . $this->volunteer->organization->name .
                            ' го смени доставувачот на донацијата.')
                    ->line('Податоци за новиот доставувач:')
                    ->line('Име и презиме: ' .  $this->volunteer->first_name . ' ' . $this->volunteer->last_name)
                    ->line('Телефон: ' .  $this->volunteer->phone)
                    ->line('Емаил: ' .  $this->volunteer->email);

        if ($this->listing_offer->volunteer->image_id) {
            $message->line('Слика:');
            $message->line('<img style="width: 150px; height: auto;" src="' . url('storage' . config('app.upload_path') . '/' . File::find($this->listing_offer->volunteer->image_id)->filename) . '" alt="Доставувач" />');
        }
        $message->line('Ви благодариме што го поддржувате нашиот труд да го намалиме отпадот од храна и недостаток на храна.');
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
