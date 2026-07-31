<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AdvertisementRepositoryInterface extends RepositoryInterface
{
    public function findByCampaign(int $campaignId, array $relations = [], int $perPage = 15): LengthAwarePaginator;

    public function findPendingByCampaign(int $campaignId, array $relations = [], int $perPage = 15): LengthAwarePaginator;
}
