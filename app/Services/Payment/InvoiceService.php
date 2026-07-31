<?php

namespace App\Services\Payment;

use App\Models\Invoice;
use App\Models\Payment;
use App\Repositories\Contracts\InvoiceRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    public function __construct(
        private InvoiceRepositoryInterface $invoiceRepository
    ) {}

    /**
     * Génère une facture à partir d'un paiement.
     */
    public function generateInvoice(Payment $payment): Invoice
    {
        $invoiceNumber = 'INV-' . strtoupper(uniqid());

        $data = [
            'advertiser_id'  => $payment->advertiser_id,
            'payment_id'     => $payment->id,
            'invoice_number' => $invoiceNumber,
            'subtotal'       => $payment->amount,
            'tax'            => 0, // à calculer via une taxe configurée
            'total'          => $payment->amount,
            'issued_date'    => now()->toDateString(),
            'due_date'       => now()->addDays(30)->toDateString(),
            'status'         => 'generated',
        ];

        $invoice = $this->invoiceRepository->create($data);

        // Générer le PDF (avec un package comme DomPDF)
        // $pdf = \PDF::loadView('invoices.template', ['invoice' => $invoice]);
        // $path = 'invoices/invoice_' . $invoice->id . '.pdf';
        // Storage::put($path, $pdf->output());
        // $invoice->update(['file_path' => $path]);

        return $invoice;
    }

    /**
     * Marque une facture comme payée.
     */
    public function markAsPaid(Invoice $invoice): void
    {
        $invoice->update(['status' => 'paid']);
    }

    /**
     * Récupère les factures d'un annonceur.
     */
    public function getForAdvertiser(int $advertiserId, int $perPage = 15)
    {
        return $this->invoiceRepository->findByAdvertiser($advertiserId, $perPage);
    }
}