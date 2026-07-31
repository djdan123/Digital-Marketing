<?php

namespace App\Repositories\Eloquent;

use App\Models\Transaction;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TransactionRepository extends BaseRepository implements TransactionRepositoryInterface
{
    public function __construct(Transaction $model)
    {
        parent::__construct($model);
    }

    public function findByUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->query()
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function findByPayment(int $paymentId): Collection
    {
        return $this->query()->where('payment_id', $paymentId)->get();
    }
}