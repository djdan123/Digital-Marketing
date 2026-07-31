<?php

namespace App\Services\Payment;

use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use App\Repositories\Contracts\TransactionRepositoryInterface;

class PaymentCommissionService
{
    private float $commissionRate = 0.10; // 10% par défaut

    public function __construct(
        private TransactionRepositoryInterface $transactionRepository
    ) {}

    /**
     * Calcule et prélève la commission sur un paiement.
     */
    public function processCommission(Payment $payment): Transaction
    {
        $commissionAmount = $payment->amount * $this->commissionRate;

        // Créer une transaction de commission (débit pour l'annonceur, crédit pour la plateforme)
        $transaction = $this->transactionRepository->create([
            'user_id'      => $payment->advertiser_id,
            'payment_id'   => $payment->id,
            'type'         => 'commission',
            'amount'       => -$commissionAmount, // débit
            'balance_after'=> 0, // à calculer en fonction du solde du wallet
            'description'  => 'Commission prélevée sur le paiement #' . $payment->id,
        ]);

        // Idéalement, on crédite également un compte "plateforme"
        // On peut aussi enregistrer une transaction pour l'admin

        return $transaction;
    }

    /**
     * Calcule le montant net à reverser au média (après commission).
     */
    public function calculateNetAmount(Payment $payment): float
    {
        return $payment->amount * (1 - $this->commissionRate);
    }

    /**
     * Met à jour le taux de commission (admin uniquement).
     */
    public function setCommissionRate(float $rate): void
    {
        $this->commissionRate = $rate;
    }
}