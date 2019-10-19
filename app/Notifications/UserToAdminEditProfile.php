<?php

namespace FSR\Notifications;

use FSR\Custom\CarbonFix;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserToAdminEditProfile extends Notification implements ShouldQueue
{
    use Queueable;
    private $user;


    /**
     * Create a new notification instance.
     * @param object $user
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
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
        switch ($this->user->type()) {
            case 'cso':
                $type = "примател";
                break;
            case 'donor':
                $type = "донатор";
                break;
            case 'hub':
                $type = "хаб";
                break;
            
            default:
                //
                break;
        }
        $message = (new MailMessage)->subject('[Сите Сити] Променет е профилот на ' . $type . 'от.')
                    ->line('Направени се следниве измени:')
                    ->line('Тип: ' . $type)
                    ->line('Име: ' . $this->user->first_name)
                    ->line('Презиме: ' . $this->user->last_name)
                    ->line('Емаил: ' . $this->user->email)
                    ->line('Организација: ' . $this->user->organization->name)
                    ->line('Адреса: ' . $this->user->address)
                    ->line('Телефон: ' . $this->user->phone);

        if ($this->user->type() == 'hub') {
            $message->line('Регион: ' . $this->user->region->name);
        } else {
            $message->line('Локација: ' . $this->user->location->name);
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
