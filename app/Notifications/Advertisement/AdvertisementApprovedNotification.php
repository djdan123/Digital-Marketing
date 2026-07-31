<?php

namespace App\Notifications\Advertisement;

use App\Models\Advertisement;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdvertisementApprovedNotification extends Notification
{
    use Queueable;

    public function __construct(public Advertisement $advertisement)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Annonce approuvée')
            ->line('Votre annonce « '.$this->advertisement->title.' » a été approuvée.')
            ->line('Elle sera programmée pour diffusion prochainement.');
    }
}
