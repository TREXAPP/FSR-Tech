<?php

namespace FSR\Notifications;

use FSR\HubListing;
use FSR\Custom\CarbonFix;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class HubToCsosAdminNewDonation extends Notification implements ShouldQueue
{
    use Queueable;


    protected $hub_listing;

    /**
     * Create a new notification instance.
     *
     * @param HubListing $hub_listing
     * @return void
     */
    public function __construct(HubListing $hub_listing)
    {
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
        return (new MailMessage)
                    ->subject('[Сите Сити] Достапна е нова донација')
                    ->line($this->hub_listing->hub->first_name . ' ' . $this->hub_listing->hub->last_name . ' - ' . $this->hub_listing->hub->organization->name .
                          ' само што ја додаде следнава донација:')
                    ->line('Производ: ' . $this->hub_listing->product->name)
                    ->line('Количина: ' . $this->hub_listing->quantity . ' ' . $this->hub_listing->quantity_type->description)
                    ->line('Важи од: ' . CarbonFix::parse($this->hub_listing->pickup_time_from)->format('d.m.Y H:i') . ' часот')
                    ->line('Достапна на платформата уште: ' . CarbonFix::parse($this->hub_listing->date_expires)->diffForHumans())
                    ->line('Време за подигнување: од ' . CarbonFix::parse($this->hub_listing->pickup_time_from)->format('H:i') . ' до ' . CarbonFix::parse($this->hub_listing->pickup_time_to)->format('H:i'))
                    ->line('Опис: ' . $this->hub_listing->description)
                    ->line('<hr>')
                    ->line('Информации за хабот:')
                    ->line('Име и презиме: ' . $this->hub_listing->hub->first_name . ' ' . $this->hub_listing->hub->last_name)
                    ->line('Организација: ' . $this->hub_listing->hub->organization->name)
                    ->line('Телефон: ' . $this->hub_listing->hub->phone)
                    ->line('Адреса: ' . $this->hub_listing->hub->address . ' - ' . $this->hub_listing->hub->region->name)
                    ->line('<hr>')
                    ->line('Ако сте заинтересирани да ја подигнете оваа донација кликнете подолу!')
                    ->action('Прифати ја донацијата', route('cso.active_listings'));
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
