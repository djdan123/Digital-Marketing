<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Advertisement;

class AdvertisementPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Advertisement $advertisement): bool
    {
        // propriétaire annonceur ou média responsable
        if ($this->isAdvertiserOwner($user, $advertisement)) {
            return true;
        }

        return isset($user->role) && $user->role === \App\Enums\UserRole::MEDIA_MANAGER->value;
    }

    public function create(User $user): bool
    {
        return isset($user->role) && $user->role === \App\Enums\UserRole::ADVERTISER->value;
    }

    public function update(User $user, Advertisement $advertisement): bool
    {
        return $this->isAdvertiserOwner($user, $advertisement);
    }

    public function delete(User $user, Advertisement $advertisement): bool
    {
        return $this->isAdvertiserOwner($user, $advertisement);
    }
}
