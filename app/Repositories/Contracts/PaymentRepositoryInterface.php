<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PaymentRepositoryInterface extends RepositoryInterface
{
    public function findByAdvertiser(int $advertiserId, int $perPage = 15): LengthAwarePaginator;

    public function findPending(int $perPage = 15): LengthAwarePaginator;
}
