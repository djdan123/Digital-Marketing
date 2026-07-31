<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function findByEmail(string $email): ?User;

    public function findByRole(string $role, int $perPage = 15): LengthAwarePaginator;
}
