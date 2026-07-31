<?php

namespace App\Notifications\Advertisement;

use App\Models\Advertisement;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdvertisementRejectedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Advertisement $advertisement,
        public ?string $comments = null,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Annonce refusée')
            ->line('Votre annonce « '.$this->advertisement->title.' » a été refusée.');

        if ($this->comments) {
            $message->line('Motif: '.$this->comments);
        }

        return $message->line('Vous pouvez modifier votre annonce et la soumettre à nouveau.');
    }
}
