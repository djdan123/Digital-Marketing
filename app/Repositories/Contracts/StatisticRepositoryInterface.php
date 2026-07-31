<?php

namespace App\Repositories\Contracts;

use App\Models\Statistic;

interface StatisticRepositoryInterface extends RepositoryInterface
{
    public function updateOrCreate(array $attributes, array $values): Statistic;
    public function getStatsForCampaign(int $campaignId, ?string $dateFrom = null, ?string $dateTo = null): \Illuminate\Database\Eloquent\Collection;
}