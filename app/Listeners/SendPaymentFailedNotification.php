<?php

namespace App\Listeners;

use App\Events\PaymentFailed;
use App\Notifications\Payment\PaymentFailedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendPaymentFailedNotification implements ShouldQueue
{
    public function handle(PaymentFailed $event): void
    {
        $payment = $event->payment;

        if ($payment->advertiser) {
            Notification::send($payment->advertiser, new PaymentFailedNotification($payment));
        }
    }
}
