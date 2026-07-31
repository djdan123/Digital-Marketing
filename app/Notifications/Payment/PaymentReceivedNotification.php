<?php

namespace App\Notifications\Payment;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PaymentReceivedNotification extends Notification
{
    use Queueable;

    public function __construct(public Payment $payment)
    {
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Paiement reçu')
            ->line('Votre paiement a été reçu.')
            ->line('Montant: '.$this->payment->amount.' '.$this->payment->currency)
            ->line('Référence: '.($this->payment->reference ?? 'N/A'));
    }
}
