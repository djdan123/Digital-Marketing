<?php

namespace App\Services\Payment;

use App\DTOs\Payment\ProcessPaymentDTO;
use App\Models\Payment;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Services\Contracts\PaymentServiceInterface;

class PaymentService implements PaymentServiceInterface
{
    public function __construct(private PaymentRepositoryInterface $paymentRepository)
    {
    }

    public function process(ProcessPaymentDTO $dto): Payment
    {
        $payment = $this->paymentRepository->create([
            'advertiser_id' => $dto->advertiser_id,
            'campaign_id' => $dto->campaign_id,
            'amount' => $dto->amount,
            'currency' => $dto->currency,
            'payment_method' => $dto->payment_method,
            'status' => $dto->status,
            'reference' => $dto->reference,
            'metadata' => $dto->metadata,
        ]);

        return $payment;
    }

    public function findByAdvertiser(int $advertiserId, int $perPage = 15)
    {
        return $this->paymentRepository->findByAdvertiser($advertiserId, $perPage);
    }

    public function findPending(int $perPage = 15)
    {
        return $this->paymentRepository->findPending($perPage);
    }
}
