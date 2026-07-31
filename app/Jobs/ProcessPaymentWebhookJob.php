<?php

namespace App\Jobs;

use App\Events\PaymentSucceeded;
use App\Events\PaymentFailed;
use App\Models\Payment;
use App\Services\Payment\PaymentService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessPaymentWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public array $payload)
    {
    }

    public function handle(PaymentService $paymentService): void
    {
        // Here we expect the payload to contain advertiser_id, campaign_id, amount, currency, payment_method, reference, status
        try {
            $dto = \App\DTOs\Payment\ProcessPaymentDTO::fromArray($this->payload);

            $payment = $paymentService->process($dto);

            if ($payment->status === 'succeeded' || $payment->status === 'success') {
                event(new PaymentSucceeded($payment));
            } else {
                event(new PaymentFailed($payment));
            }
        } catch (Exception $e) {
            // Log and rethrow to allow retry
            report($e);
            throw $e;
        }
    }
}
