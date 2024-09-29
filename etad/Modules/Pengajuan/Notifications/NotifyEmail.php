<?php

namespace Modules\Pengajuan\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NotifyEmail extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->from('no-reply@bankjatim.co.id', 'e-TAD (SIS Tenaga Ahli Daya)')
            ->subject('Pemberitahuan Undangan Interview')
            ->markdown('vendor.notifications.email')
            ->greeting('Undangan Interview')
            ->line('The introduction to the notification.')
            ->line("");
    }
}
