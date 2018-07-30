<?php

namespace FSR\Notifications;

use FSR\Volunteer;
use FSR\Custom\CarbonFix;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class FreeVolunteerApplicationSuccess extends Notification implements ShouldQueue
{
    use Queueable;



    /**
     * Create a new notification instance.
     * @param object $user
     * @return void
     */
    public function __construct()
    {
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
                    ->subject('[Сите Сити] Добредојдовте на платформата Сите Сити')
                    ->line('Би сакале да Ви се заблагодариме за желбата да се приклучите на мрежата Сите Сити како волонтер.')
                    ->line('Член од нашиот тим ќе Ве контактира директно по телефон или емаил за повеќе детали.');
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
