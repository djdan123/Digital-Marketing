<?php

namespace App\Listeners;

use App\Events\PaymentSucceeded;
use App\Jobs\GenerateInvoiceJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Bus;

class GenerateInvoice implements ShouldQueue
{
    public function handle(PaymentSucceeded $event): void
    {
        $payment = $event->payment;

        // Dispatch job to generate invoice
        Bus::dispatch(new GenerateInvoiceJob($payment));
    }
}
