<?php

namespace App\Notifications\Broadcast;

use App\Models\Broadcast;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BroadcastCompletedNotification extends Notification
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
            ->subject('Diffusion confirmée')
            ->line('La diffusion sur '.$mediaName.' a été confirmée.')
            ->line('Date: '.$this->broadcast->broadcasted_at?->format('d/m/Y H:i'))
            ->line('Une preuve de diffusion peut être consultée dans votre espace.');
    }
}
