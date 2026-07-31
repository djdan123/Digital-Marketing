<?php

namespace App\Repositories\Eloquent;

use App\Models\Payment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PaymentRepository extends BaseRepository implements \App\Repositories\Contracts\PaymentRepositoryInterface
{
    public function __construct(Payment $model)
    {
        parent::__construct($model);
    }

    public function findByAdvertiser(int $advertiserId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->query()
            ->where('advertiser_id', $advertiserId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function findPending(int $perPage = 15): LengthAwarePaginator
    {
        return $this->query()
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}
