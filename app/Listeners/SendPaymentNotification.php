<?php

namespace App\Listeners;

use App\Events\PaymentSucceeded;
use App\Notifications\Payment\PaymentReceivedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendPaymentNotification implements ShouldQueue
{
    public function handle(PaymentSucceeded $event): void
    {
        $payment = $event->payment;

        // Notify advertiser
        if ($payment->advertiser) {
            Notification::send($payment->advertiser, new PaymentReceivedNotification($payment));
        }
    }
}
