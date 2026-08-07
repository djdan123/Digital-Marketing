<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminBroadcastNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public array $payload) {}

    public function via(object $notifiable): array
    {
        $channels = ['database'];

        if (($this->payload['channel'] ?? '') === 'email') {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->payload['title'] ?? 'Notification TruckAll')
            ->line($this->payload['message'] ?? '');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title'    => $this->payload['title'] ?? null,
            'message'  => $this->payload['message'] ?? null,
            'channel'  => $this->payload['channel'] ?? 'database',
            'audience' => $this->payload['audience'] ?? null,
        ];
    }
}