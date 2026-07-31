<?php

namespace App\Repositories\Contracts;

use App\Models\Transaction;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TransactionRepositoryInterface extends RepositoryInterface
{
    public function findByUser(int $userId, int $perPage = 15): LengthAwarePaginator;
    public function findByPayment(int $paymentId): \Illuminate\Database\Eloquent\Collection;
}