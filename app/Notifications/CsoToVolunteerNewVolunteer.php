<?php

namespace FSR\Notifications;

use FSR\Cso;
use FSR\File;
use FSR\Volunteer;
use FSR\Custom\CarbonFix;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CsoToVolunteerNewVolunteer extends Notification
{
    use Queueable;


    private $volunteer;
    private $cso;

    /**
     * Create a new notification instance.
     * @param Volunteer $volunteer
     * @param Cso $cso
     * @return void
     */
    public function __construct(Volunteer $volunteer, Cso $cso)
    {
        $this->volunteer = $volunteer;
        $this->cso = $cso;
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
                    ->subject('[Сите Сити] Добредојдовте на платформата')
                    ->line('Додадени сте од ' . $this->cso->first_name . ' ' . $this->cso->last_name .
                            ' - ' . $this->volunteer->organization->name .
                          ', за да помогнете како доставувач во собирање и редистрибуцијата на донација на вишок на храна. ')
                    ->line('Ви благодариме за учеството. Ве молиме потврдете ја точноста на вашите внесени информации. ')
                    ->line('Име: ' . $this->volunteer->first_name)
                    ->line('Презиме: ' . $this->volunteer->last_name)
                    ->line('Телефон: ' . $this->volunteer->phone)
                    ->line('Емаил: ' . $this->volunteer->email);

        if ($this->volunteer->image_id) {
            $message->line('Слика: ')
                  ->line('<img style="width: 150px; height: auto;" src="' . url('storage' . config('app.upload_path') . '/' . File::find($this->volunteer->image_id)->filename) . '" alt="Доставувач" />')
                  ->line('');
        }
        $message->line('Ако било која од овие информации е неточна или сте додадени по грешка Ве молиме контактирајте ja организацијата на телефонскиот број ' . $this->cso->phone . '.');

        return $message;

        // ->action('Види ги промените', url('/cso/profile'));
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
