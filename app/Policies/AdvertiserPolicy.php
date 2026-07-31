<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Advertiser;

class AdvertiserPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return isset($user->role) && $user->role === \App\Enums\UserRole::ADMIN->value;
    }

    public function view(User $user, Advertiser $advertiser): bool
    {
        return $user->id === $advertiser->user_id;
    }

    public function create(User $user): bool
    {
        // Any authenticated user may register as advertiser (application-specific)
        return true;
    }

    public function update(User $user, Advertiser $advertiser): bool
    {
        return $user->id === $advertiser->user_id;
    }

    public function delete(User $user, Advertiser $advertiser): bool
    {
        return $user->id === $advertiser->user_id;
    }
}
