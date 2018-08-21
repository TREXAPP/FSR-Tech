<?php

namespace FSR\Notifications;

use FSR\Volunteer;
use FSR\Custom\CarbonFix;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class FreeVolunteerToAdminsRegister extends Notification implements ShouldQueue
{
    use Queueable;

    private $free_volunteer;

    /**
     * Create a new notification instance.
     * @param object $user
     * @return void
     */
    public function __construct($free_volunteer)
    {
        $this->free_volunteer = $free_volunteer;
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
        if ($this->free_volunteer->type = "transport_food") {
            $type = "Пренесување на храна";
        } elseif ($this->free_volunteer->type = "build_relationships") {
            $type = "Градење врски";
        }

        $locationsString = '';
        foreach ($this->free_volunteer->locations as $location) {
            $locationsString .= $location->name . ', ';
        }
        $mail = (new MailMessage)
                    ->subject('[Сите Сити] Нов волонтер аплицираше')
                    ->line('Информации за волонтерот:')
                    ->line('Тип на волонтер: ' . $type)
                    ->line('Име: ' . $this->free_volunteer->first_name)
                    ->line('Презиме: ' . $this->free_volunteer->last_name)
                    ->line('Емаил: ' . $this->free_volunteer->email)
                    ->line('Телефон: ' . $this->free_volunteer->phone)
                    ->line('Адреса: ' . $this->free_volunteer->address)
                    ->line('Општини: ' . $locationsString);

        if ($this->free_volunteer->type = "transport_food") {
            $transportTypesString = '';
            foreach ($this->free_volunteer->transport_types as $transport_type) {
                $transportTypesString .= $transport_type->name . ' (' . $transport_type->quantity . '), ';
            }
            $mail->line('Типови на транспорт: ' . $transportTypesString);

            $organizationsString = '';
            foreach ($this->free_volunteer->free_organizations as $organization) {
                $organizationsString .= $organization->name . ', ';
            }
            $mail->line('Организации за кои волонтерот избрал да волонтира: ' . $organizationsString);

            $timeframesString = '';
            foreach ($this->free_volunteer->timeframes as $timeframe) {
                $timeframesString .= $timeframe->day . ' од ' . $timeframe->hours_from . ' до ' . $timeframe->hours_to . '; ';
            }
            $mail->line('Периоди во кои волонтерот е достапен: ' . $timeframesString);
        }

        return $mail;
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
