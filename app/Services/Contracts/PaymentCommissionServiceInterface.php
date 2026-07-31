<?php

namespace App\Services\Contracts;

use App\Models\Payment;
use App\Models\Transaction;

interface PaymentCommissionServiceInterface extends ServiceInterface
{
    /**
     * Calcule et prélève la commission sur un paiement.
     */
    public function processCommission(Payment $payment): Transaction;

    /**
     * Calcule le montant net (après commission).
     */
    public function calculateNetAmount(Payment $payment): float;

    /**
     * Met à jour le taux de commission.
     */
    public function setCommissionRate(float $rate): void;
}