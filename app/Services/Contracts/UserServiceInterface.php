<?php

namespace App\Services\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\User;

interface UserServiceInterface extends ServiceInterface
{
    public function findByEmail(string $email): ?User;

    public function findByRole(string $role, int $perPage = 15): LengthAwarePaginator;
}
