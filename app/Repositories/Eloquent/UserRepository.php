<?php

namespace App\Repositories\Eloquent;

use App\Models\User;

class UserRepository extends BaseRepository implements \App\Repositories\Contracts\UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->query()->where('email', $email)->first();
    }

    public function findByRole(string $role, int $perPage = 15)
    {
        return $this->query()
            ->where('role', $role)
            ->orderBy('name')
            ->paginate($perPage);
    }
}
