<?php

namespace App\Services\User;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\UserServiceInterface;
use App\Models\User;

class UserService implements UserServiceInterface
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function findByEmail(string $email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }

    public function findByRole(string $role, int $perPage = 15)
    {
        return $this->userRepository->findByRole($role, $perPage);
    }
}
