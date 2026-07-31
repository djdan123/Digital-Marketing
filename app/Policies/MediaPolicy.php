<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Media;

class MediaPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Media $media): bool
    {
        // media managers or admins
        return $this->before($user, 'view') ?? (isset($user->role) && $user->role === \App\Enums\UserRole::MEDIA_MANAGER->value);
    }

    public function create(User $user): bool
    {
        return $this->before($user, 'create') ?? false;
    }

    public function update(User $user, Media $media): bool
    {
        return $this->before($user, 'update') ?? (isset($user->company_id) && $user->company_id === $media->company_id);
    }

    public function delete(User $user, Media $media): bool
    {
        return $this->before($user, 'delete') ?? (isset($user->company_id) && $user->company_id === $media->company_id);
    }
}
