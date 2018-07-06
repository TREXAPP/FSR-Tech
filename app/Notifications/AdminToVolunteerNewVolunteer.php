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
                  ->subject('[Сите Сити] Добредојдовте на платформата')
                  ->line('Ајде Македонија ве додаде како доставувач на ' . $this->volunteer->organization->name .
                        ',  за подигнување и редистрибуција на донациите за вишок храна.')
                  ->line('Ќе добиете е-мајл известување кога ќе бидете одбрани да подигнете донација со детали за предметот и компанијата. ')
                  ->line('Ве молиме потврдете ја точноста на вашите внесени информации. ')
                  ->line('Име: ' . $this->volunteer->first_name)
                  ->line('Презиме: ' . $this->volunteer->last_name)
                  ->line('Телефон: ' . $this->volunteer->phone)
                  ->line('Емаил: ' . $this->volunteer->email);

        if ($this->volunteer->image_id) {
            $message->line('Слика: ')
                ->line('<img style="width: 150px; height: auto;" src="' . url('storage' . config('app.upload_path') . '/' . File::find($this->volunteer->image_id)->filename) . '" alt="Доставувач" />')
                ->line('');
        }
        $message->line('Ако информациите не се точни контактирајте не` директно на <a href="mailto:' . config('app.master_admin') . '">' . config('app.master_admin') . '</a>');

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
