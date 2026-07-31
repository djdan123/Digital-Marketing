<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface MediaRepositoryInterface extends RepositoryInterface
{
    public function findActiveByType(string $type, int $perPage = 15): LengthAwarePaginator;
}
