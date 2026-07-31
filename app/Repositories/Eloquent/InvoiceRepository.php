<?php

namespace App\Repositories\Eloquent;

use App\Models\Invoice;
use App\Repositories\Contracts\InvoiceRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class InvoiceRepository extends BaseRepository implements InvoiceRepositoryInterface
{
    public function __construct(Invoice $model)
    {
        parent::__construct($model);
    }

    public function findByAdvertiser(int $advertiserId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->query()
            ->where('advertiser_id', $advertiserId)
            ->orderBy('issued_date', 'desc')
            ->paginate($perPage);
    }

    public function findByPayment(int $paymentId): ?Invoice
    {
        return $this->query()->where('payment_id', $paymentId)->first();
    }
}