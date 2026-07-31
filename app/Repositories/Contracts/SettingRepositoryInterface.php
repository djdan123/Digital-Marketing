<?php

namespace App\Repositories\Contracts;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Collection;

interface SettingRepositoryInterface extends RepositoryInterface
{
    public function findByKey(string $key): ?Setting;
    public function findByGroup(string $group): Collection;
}