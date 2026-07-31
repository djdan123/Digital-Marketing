<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface InvoiceRepositoryInterface extends RepositoryInterface
{
    public function findByAdvertiser(int $advertiserId, int $perPage = 15): LengthAwarePaginator;
    public function findByPayment(int $paymentId): ?\App\Models\Invoice;
}