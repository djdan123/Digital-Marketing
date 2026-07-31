<?php

namespace App\Notifications\Payment;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentFailedNotification extends Notification
{
    use Queueable;

    public function __construct(public Payment $payment)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Échec du paiement')
            ->line('Votre paiement n\'a pas pu être traité.')
            ->line('Montant: '.$this->payment->amount.' '.$this->payment->currency)
            ->line('Référence: '.($this->payment->reference ?? 'N/A'))
            ->line('Veuillez réessayer ou contacter le support.');
    }
}
