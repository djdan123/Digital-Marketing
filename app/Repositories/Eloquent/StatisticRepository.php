<?php

namespace App\Repositories\Eloquent;

use App\Models\Statistic;
use App\Repositories\Contracts\StatisticRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class StatisticRepository extends BaseRepository implements StatisticRepositoryInterface
{
    public function __construct(Statistic $model)
    {
        parent::__construct($model);
    }

    public function updateOrCreate(array $attributes, array $values): Statistic
    {
        return $this->model->updateOrCreate($attributes, $values);
    }

    public function getStatsForCampaign(int $campaignId, ?string $dateFrom = null, ?string $dateTo = null): Collection
    {
        $query = $this->query()->where('campaign_id', $campaignId);
        if ($dateFrom) $query->where('date', '>=', $dateFrom);
        if ($dateTo) $query->where('date', '<=', $dateTo);
        return $query->orderBy('date')->get();
    }
}