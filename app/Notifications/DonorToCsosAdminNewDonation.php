<?php

namespace FSR\Notifications;

use FSR\Listing;
use FSR\Custom\CarbonFix;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DonorToCsosAdminNewDonation extends Notification implements ShouldQueue
{
    use Queueable;


    protected $listing;

    /**
     * Create a new notification instance.
     *
     * @param Listing $listing
     * @return void
     */
    public function __construct(Listing $listing)
    {
        $this->listing = $listing;
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
                    ->subject('Достапна е нова донација')
                    ->line($this->listing->donor->first_name . ' ' . $this->listing->donor->last_name . ' - ' . $this->listing->donor->organization->name .
                          ' само што ја додаде следнава донација:')
                    ->line('Производ: ' . $this->listing->product->name)
                    ->line('Количина: ' . $this->listing->quantity . ' ' . $this->listing->quantity_type->description)
                    ->line('Важи од: ' . CarbonFix::parse($this->listing->pickup_time_from)->format('d.m.Y H:i') . ' часот')
                    ->line('Достапна на платформата уште: ' . CarbonFix::parse($this->listing->date_expires)->diffForHumans())
                    ->line('Време за подигнување: од ' . CarbonFix::parse($this->listing->pickup_time_from)->format('H:i') . ' до ' . CarbonFix::parse($this->listing->pickup_time_to)->format('H:i'))
                    ->line('Опис: ' . $this->listing->description)
                    ->line('<hr>')
                    ->line('Информации за донаторот:')
                    ->line('Име и презиме: ' . $this->listing->donor->first_name . ' ' . $this->listing->donor->last_name)
                    ->line('Организација: ' . $this->listing->donor->organization->name)
                    ->line('Телефон: ' . $this->listing->donor->phone)
                    ->line('Адреса: ' . $this->listing->donor->address)
                    ->line('<hr>')
                    ->line('Ако сте заинтересирани да ја подигнете оваа донација кликнете подолу!')
                    ->action('Прифати ја донацијата', url('/cso/active_listings/'));
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
