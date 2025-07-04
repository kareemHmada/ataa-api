<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmMessage;


class DonationApprovedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    // public function via(object $notifiable): array
    // {
    //     return ['mail'];
    // }
    public function via($notifiable)
    {
        return ['fcm', 'database']; 
    }

    public function toFcm($notifiable)
    {
        return (new FcmMessage)
            ->content([
                'title' => 'تم قبول طلبك!',
                'body' => 'تهانينا، تم قبول طلب التبرع الخاص بك.',
            ])
            ->data([
                'type' => 'donation_approved',
                'user_id' => $notifiable->id,
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
 public function toArray($notifiable)
{
    return [
        'title' => 'تم قبول طلبك!',
        'body'  => 'تهانينا، تم قبول طلب التبرع الخاص بك.',
        'type'  => 'donation_approved',
    ];
}
   
}
