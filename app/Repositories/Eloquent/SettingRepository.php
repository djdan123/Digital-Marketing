<?php

namespace App\Repositories\Eloquent;

use App\Models\Setting;
use App\Repositories\Contracts\SettingRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class SettingRepository extends BaseRepository implements SettingRepositoryInterface
{
    public function __construct(Setting $model)
    {
        parent::__construct($model);
    }

    public function findByKey(string $key): ?Setting
    {
        return $this->query()->where('key', $key)->first();
    }

    public function findByGroup(string $group): Collection
    {
        return $this->query()->where('group', $group)->get();
    }
}