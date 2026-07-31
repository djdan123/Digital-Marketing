<?php

namespace App\Jobs;

use App\Models\Invoice;
use App\Models\Payment;
use App\Notifications\Payment\InvoiceGeneratedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class GenerateInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Payment $payment)
    {
    }

    public function handle(): void
    {
        $invoice = Invoice::create([
            'advertiser_id' => $this->payment->advertiser_id,
            'invoice_number' => 'INV-'.strtoupper(uniqid()),
            'issued_at' => now(),
            'due_at' => now()->addDays(30),
            'subtotal' => $this->payment->amount,
            'tax' => 0,
            'total' => $this->payment->amount,
            'status' => 'issued',
            'line_items' => [
                [
                    'description' => 'Campagne publicitaire #'.$this->payment->campaign_id,
                    'amount' => $this->payment->amount,
                ],
            ],
        ]);

        if ($this->payment->advertiser) {
            Notification::send(
                $this->payment->advertiser,
                new InvoiceGeneratedNotification($invoice)
            );
        }
    }
}
