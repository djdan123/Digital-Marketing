<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;
use App\Enums\UserRole;

abstract class BasePolicy
{
    use HandlesAuthorization;

    /**
     * Admin override: si l'utilisateur est administrateur, autoriser tout.
     */
    public function before(User $user, string $ability)
    {
        $role = $user->getAttribute('role') ?? null;

        if ($role === UserRole::ADMIN->value) {
            return true;
        }
    }

    /**
     * Vérifie si l'utilisateur est propriétaire via la relation Advertiser
     */
    protected function isAdvertiserOwner(User $user, object $model): bool
    {
        // related advertiser -> user_id
        $advUserId = $model->advertiser?->user_id ?? null;
        if ($advUserId === $user->id) {
            return true;
        }

        // fallback: model has user_id
        if (property_exists($model, 'user_id') && $model->user_id === $user->id) {
            return true;
        }

        return false;
    }
}
