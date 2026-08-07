<?php

namespace App\Services\Payment;

use App\Models\Payment;
use App\Models\Transaction;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use App\Services\Contracts\PaymentCommissionServiceInterface;

class PaymentCommissionService implements PaymentCommissionServiceInterface
{
    private float $commissionRate;

    public function __construct(
        private TransactionRepositoryInterface $transactionRepository
    ) {
        $this->commissionRate = (float) config('truckall.commission_rate', 0.15);
    }

    /**
     * Calcule et enregistre la commission sur un paiement réussi.
     */
    public function processCommission(Payment $payment): Transaction
    {
        $existingCommission = $this->transactionRepository->findByPayment($payment->id)
            ->first(fn (Transaction $transaction) => $transaction->type === 'commission');

        if ($existingCommission) {
            return $existingCommission;
        }

        $commissionRate = $this->commissionRate > 0 ? $this->commissionRate : (float) config('truckall.commission_rate', 0.15);
        $commissionAmount = round((float) $payment->amount * $commissionRate, 4);

        return $this->transactionRepository->create([
            'payment_id' => $payment->id,
            'amount' => $commissionAmount,
            'currency' => $payment->currency ?? 'USD',
            'type' => 'commission',
            'reference' => 'platform_commission_' . $payment->id,
            'details' => [
                'description' => 'Commission plateforme sur le paiement #' . $payment->id,
                'commission_rate' => $commissionRate,
            ],
        ]);
    }

    /**
     * Calcule le montant net à reverser au média (après commission).
     */
    public function calculateNetAmount(Payment $payment): float
    {
        $rate = $this->commissionRate > 0 ? $this->commissionRate : (float) config('truckall.commission_rate', 0.15);

        return (float) $payment->amount * (1 - $rate);
    }

    /**
     * Met à jour le taux de commission (admin uniquement).
     */
    public function setCommissionRate(float $rate): void
    {
        $this->commissionRate = $rate;
    }
}