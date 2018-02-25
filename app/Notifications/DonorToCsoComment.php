<?php

namespace FSR\Notifications;

use FSR\Volunteer;
use FSR\Custom\CarbonFix;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DonorToCsoComment extends Notification implements ShouldQueue
{
    use Queueable;

    private $listing_offer_id;
    private $comment_text;

    /**
     * Create a new notification instance.
     * @param int $listing_offer_id
     * @param string $comment_text
     * @return void
     */
    public function __construct(int $listing_offer_id, string $comment_text)
    {
        $this->listing_offer_id = $listing_offer_id;
        $this->comment_text = $comment_text;
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
                    ->subject('Нов коментар на прифатена донација!')
                    ->line('Имате нов коментар на вашата прифатена донација:')
                    ->line('"' . $this->comment_text . '"')
                    ->action('Кон коментарот', route('cso.accepted_listings.single_accepted_listing', $this->listing_offer_id) . '#comments');
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
