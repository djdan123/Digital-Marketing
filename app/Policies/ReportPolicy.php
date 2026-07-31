<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Report;

class ReportPolicy extends BasePolicy
{
	public function viewAny(User $user): bool
	{
		return isset($user->role) && in_array($user->role, [\App\Enums\UserRole::ADMIN->value, \App\Enums\UserRole::ACCOUNTANT->value, \App\Enums\UserRole::MEDIA_MANAGER->value]);
	}

	public function view(User $user, Report $report): bool
	{
		return $this->before($user, 'view') ?? true;
	}

	public function create(User $user): bool
	{
		return isset($user->role) && in_array($user->role, [\App\Enums\UserRole::ADMIN->value, \App\Enums\UserRole::ACCOUNTANT->value]);
	}

	public function delete(User $user, Report $report): bool
	{
		return $this->before($user, 'delete') ?? false;
	}
}

