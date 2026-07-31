<?php

namespace App\Services\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Media;

interface MediaServiceInterface extends ServiceInterface
{
    public function allActiveByType(string $type, int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?Media;
}
