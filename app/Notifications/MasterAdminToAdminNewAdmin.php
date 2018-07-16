<?php

namespace FSR\Notifications;

use FSR\File;
use FSR\Admin;
use FSR\Custom\CarbonFix;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class MasterAdminToAdminNewAdmin extends Notification
{
    use Queueable;


    private $admin;

    /**
     * Create a new notification instance.
     * @param Admin $admin
     * @return void
     */
    public function __construct(Admin $admin)
    {
        $this->admin = $admin;
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
                  ->line('Додадени сте како администратор на платформата Сите Сити.')
                  ->line('Можете да се логирате на следниот линк: ' . route('admin.login'))
                  ->line('Ве молиме потврдете ја точноста на вашите внесени информации. ')
                  ->line('Име: ' . $this->admin->first_name)
                  ->line('Презиме: ' . $this->admin->last_name)
                  ->line('Емаил: ' . $this->admin->email);

        if ($this->admin->profile_image_id) {
            $message->line('Слика: ')
                ->line('<img style="width: 150px; height: auto;" src="' . url('storage' . config('app.upload_path') . '/' . File::find($this->admin->profile_image_id)->filename) . '" alt="Администратор" />')
                ->line('');
        }
        $message->line('Контактирајте со главниот администратор за да ја добиете вашата лозинка. ')
                ->line('Ако информациите не се точни контактирајте не` директно на <a href="mailto:' . config('app.master_admin') . '">' . config('app.master_admin') . '</a>');

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
