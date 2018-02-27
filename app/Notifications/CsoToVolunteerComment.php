<?php

namespace FSR\Notifications;

use FSR\Volunteer;
use FSR\Custom\CarbonFix;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CsoToVolunteerComment extends Notification implements ShouldQueue
{
    use Queueable;

    private $listing_offer_id;
    private $comment_text;
    private $cso;
    private $donor;

    /**
     * Create a new notification instance.
     * @param int $listing_offer_id
     * @param string $comment_text
     * @return void
     */
    public function __construct(int $listing_offer_id, string $comment_text, $cso, $donor)
    {
        $this->listing_offer_id = $listing_offer_id;
        $this->comment_text = $comment_text;
        $this->cso = $cso;
        $this->donor = $donor;
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
                    ->subject('Нов коментар од примателот!')
                    ->line('Има нов коментар од примателот на донацијата за која сте повикан да ја подигнете:')
                    ->line('"' . $this->comment_text . '"')
                    ->line('----------------')
                    ->line('Податоци за донорот:')
                    ->line('Име и презиме: ' . $this->donor->first_name . ' ' . $this->donor->last_name)
                    ->line('Организација: ' . $this->donor->organization->name)
                    ->line('Телефон: ' . $this->donor->phone)
                    ->line('Емаил: ' . $this->donor->email)
                    ->line('Адреса: ' . $this->donor->address . ' - ' . $this->donor->location->name);
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
