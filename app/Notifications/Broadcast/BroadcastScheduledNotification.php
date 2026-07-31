<?php

namespace App\Notifications\Broadcast;

use App\Models\Broadcast;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BroadcastScheduledNotification extends Notification
{
    use Queueable;

    public function __construct(public Broadcast $broadcast)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mediaName = $this->broadcast->media?->name ?? 'Média';

        return (new MailMessage)
            ->subject('Diffusion programmée')
            ->line('Une nouvelle diffusion a été programmée sur '.$mediaName.'.')
            ->line('Date prévue: '.$this->broadcast->broadcasted_at?->format('d/m/Y H:i'))
            ->line('Statut: '.$this->broadcast->status);
    }
}
