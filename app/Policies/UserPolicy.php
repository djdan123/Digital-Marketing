<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy extends BasePolicy
{
	public function viewAny(User $user): bool
	{
		return isset($user->role) && $user->role === \App\Enums\UserRole::ADMIN->value;
	}

	public function view(User $user, User $model): bool
	{
		return $this->before($user, 'view') ?? $user->id === $model->id;
	}

	public function update(User $user, User $model): bool
	{
		return $this->before($user, 'update') ?? $user->id === $model->id;
	}

	public function delete(User $user, User $model): bool
	{
		return $this->before($user, 'delete') ?? false;
	}
}

