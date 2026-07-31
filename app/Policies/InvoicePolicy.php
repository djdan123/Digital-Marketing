<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Invoice;

class InvoicePolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return isset($user->role) && in_array($user->role, [\App\Enums\UserRole::ADMIN->value, \App\Enums\UserRole::ACCOUNTANT->value]);
    }

    public function view(User $user, Invoice $invoice): bool
    {
        if ($this->before($user, 'view')) {
            return true;
        }

        return isset($user->id) && isset($invoice->advertiser) && $invoice->advertiser->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return isset($user->role) && $user->role === \App\Enums\UserRole::ACCOUNTANT->value;
    }

    public function update(User $user, Invoice $invoice): bool
    {
        return $this->before($user, 'update') ?? false;
    }

    public function delete(User $user, Invoice $invoice): bool
    {
        return $this->before($user, 'delete') ?? false;
    }
}
