<?php

namespace App\Notifications\Payment;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceGeneratedNotification extends Notification
{
    use Queueable;

    public function __construct(public Invoice $invoice)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Facture générée')
            ->line('Votre facture '.$this->invoice->invoice_number.' a été générée.')
            ->line('Montant total: '.$this->invoice->total)
            ->line('Date d\'émission: '.$this->invoice->issued_at?->format('d/m/Y'));
    }
}
