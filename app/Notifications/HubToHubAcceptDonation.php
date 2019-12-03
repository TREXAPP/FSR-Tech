<?php

namespace FSR\Notifications;

use FSR\File;
use FSR\HubListingOffer;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use FSR\Custom\CarbonFix;

class HubToHubAcceptDonation extends Notification
{
    use Queueable;

    protected $hub_listing_offer;
    protected $donor;
    protected $reposting;
    protected $hub_listing;

    /**
     * Create a new notification instance.
     * @param HubListingOffer $hub_listing_offer
     * @return void
     */
    public function __construct(HubListingOffer $hub_listing_offer, bool $reposting, $hub_listing)
    {
        $this->hub_listing_offer = $hub_listing_offer;
        $this->donor = $hub_listing_offer->listing->donor;
        $this->reposting = $reposting;
        $this->hub_listing = $hub_listing;
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
                  ->line('Производ: ' . $this->hub_listing_offer->listing->product->name)
                  ->line('Количина: ' . $this->hub_listing_offer->quantity . ' ' . $this->hub_listing_offer->listing->quantity_type->description)
                  ->line('Време за подигање од ' . CarbonFix::parse($this->hub_listing_offer->listing->pickup_time_from)->format('H:i') . ' до ' . CarbonFix::parse($this->hub_listing_offer->listing->pickup_time_to)->format('H:i'))
                  ->line('<hr>')
                  ->line('Податоци за донорот')
                  ->line('Име и презиме: ' . $this->donor->first_name . ' ' . $this->donor->last_name)
                  ->line('Организација: ' . $this->donor->organization->name)
                  ->line('Телефон: ' . $this->donor->phone)
                  ->line('Емаил: ' . $this->donor->email)
                  ->line('Адреса: ' . $this->donor->address . ' - ' . $this->donor->location->name)
                  ->line('<hr>');

        $message->line('<br>')
                ->action('Кон прифатената донација', route('hub.accepted_listings.single_accepted_listing', $this->hub_listing_offer->id));
                     
        if ($this->reposting) {
            $message->line('<b>Донацијата е реобјавена за примателите</b>')
                    ->line('Реобјавена количина: ' . $this->hub_listing->quantity . ' ' . $this->hub_listing->quantity_type->description)
                    ->line('Важи од: ' . CarbonFix::parse($this->hub_listing->pickup_time_from)->format('d.m.Y H:i') . ' часот')
                    ->line('Достапна на платформата уште: ' . CarbonFix::parse($this->hub_listing->date_expires)->diffForHumans())
                    ->line('Време за подигнување: од ' . CarbonFix::parse($this->hub_listing->pickup_time_from)->format('H:i') . ' до ' . CarbonFix::parse($this->hub_listing->pickup_time_to)->format('H:i'))
                    ->line('Опис: ' . $this->hub_listing->description)
                    ->action('Кон објавените донации', route('hub.active_listings'));
        }

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
