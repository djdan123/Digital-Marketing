<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Schedule;

class SchedulePolicy extends BasePolicy
{
	public function viewAny(User $user): bool
	{
		return true;
	}

	public function view(User $user, Schedule $schedule): bool
	{
		// Company media managers or admins
		return $this->before($user, 'view') ?? (isset($user->company_id) && $user->company_id === $schedule->media?->company_id);
	}

	public function create(User $user): bool
	{
		return $this->before($user, 'create') ?? (isset($user->role) && $user->role === \App\Enums\UserRole::MEDIA_MANAGER->value);
	}

	public function update(User $user, Schedule $schedule): bool
	{
		return $this->before($user, 'update') ?? (isset($user->company_id) && $user->company_id === $schedule->media?->company_id);
	}

	public function delete(User $user, Schedule $schedule): bool
	{
		return $this->before($user, 'delete') ?? false;
	}
}

