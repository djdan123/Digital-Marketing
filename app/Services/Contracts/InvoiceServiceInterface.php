<?php

namespace App\Services\Contracts;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface InvoiceServiceInterface extends ServiceInterface
{
    /**
     * Génère une facture à partir d'un paiement.
     */
    public function generateInvoice(Payment $payment): Invoice;

    /**
     * Marque une facture comme payée.
     */
    public function markAsPaid(Invoice $invoice): void;

    /**
     * Récupère les factures d'un annonceur.
     */
    public function getForAdvertiser(int $advertiserId, int $perPage = 15): LengthAwarePaginator;
}