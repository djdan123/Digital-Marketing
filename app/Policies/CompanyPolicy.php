<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Company;

class CompanyPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return isset($user->role) && $user->role === \App\Enums\UserRole::ADMIN->value;
    }

    public function view(User $user, Company $company): bool
    {
        // Admins or company owners (if implemented)
        return $this->before($user, 'view') ?? (isset($user->company_id) && $user->company_id === $company->id);
    }

    public function create(User $user): bool
    {
        return isset($user->role) && $user->role === \App\Enums\UserRole::ADMIN->value;
    }

    public function update(User $user, Company $company): bool
    {
        return $this->before($user, 'update') ?? (isset($user->company_id) && $user->company_id === $company->id);
    }

    public function delete(User $user, Company $company): bool
    {
        return isset($user->role) && $user->role === \App\Enums\UserRole::ADMIN->value;
    }
}
