<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmMessage;


class GlobalAnnouncement extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */

    protected $title;
    protected $body;
    public function __construct($title, $body)
    {
        $this->title = $title;
        $this->body  = $body;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['fcm'];
    }

        public function toFcm($notifiable)
    {
        return (new FcmMessage)->content([
            'title' => $this->title,
            'body'  => $this->body,
        ])->data([
            'type' => 'announcement',
        ]);
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
