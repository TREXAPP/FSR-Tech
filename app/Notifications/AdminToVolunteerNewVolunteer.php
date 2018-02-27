<?php

namespace FSR\Notifications;

use FSR\File;
use FSR\Volunteer;
use FSR\Custom\CarbonFix;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AdminToVolunteerNewVolunteer extends Notification
{
    use Queueable;


    private $volunteer;

    /**
     * Create a new notification instance.
     * @param Volunteer $volunteer
     * @return void
     */
    public function __construct(Volunteer $volunteer)
    {
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
                  ->subject('Додадени сте како волонтер!')
                  ->line('Успешно сте додадени во системот за донирање храна како волонтер на организацијата ' .
                          $this->volunteer->organization->name . '.')
                  ->line('Вашите податоци во системот: ')
                  ->line('Име: ' . $this->volunteer->first_name)
                  ->line('Презиме: ' . $this->volunteer->last_name)
                  ->line('Телефон: ' . $this->volunteer->phone)
                  ->line('Емаил: ' . $this->volunteer->email);

        if ($this->volunteer->image_id) {
            $message->line('<img src="' . url('storage' . config('app.upload_path') . '/' . File::find($this->volunteer->image_id)->filename) . '" alt="Волонтер" />');
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
