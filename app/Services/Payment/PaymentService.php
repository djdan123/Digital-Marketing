<?php

namespace App\Services\Payment;

use App\DTOs\Payment\ProcessPaymentDTO;
use App\Models\Payment;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Services\Contracts\PaymentCommissionServiceInterface;
use App\Services\Contracts\PaymentServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PaymentService implements PaymentServiceInterface
{
    public function __construct(
        private PaymentRepositoryInterface $paymentRepository,
        private PaymentCommissionServiceInterface $paymentCommissionService,
    ) {
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

        $successfulStatuses = ['completed', 'paid', 'succeeded', 'success'];
        if (in_array($payment->status, $successfulStatuses, true)) {
            $this->paymentCommissionService->processCommission($payment);
        } elseif ($payment->status === 'refunded') {
            $payment->transactions()->where('type', 'commission')->delete();
        }

        return $payment;
    }

    public function findByAdvertiser(int $advertiserId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->paymentRepository->findByAdvertiser($advertiserId, $perPage);
    }

    public function findPending(int $perPage = 15): LengthAwarePaginator
    {
        return $this->paymentRepository->findPending($perPage);
    }
}
