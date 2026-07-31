<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CampaignRepositoryInterface extends RepositoryInterface
{
    public function findByAdvertiser(int $advertiserId, array $relations = [], int $perPage = 15): LengthAwarePaginator;

    public function findActiveByAdvertiser(int $advertiserId, int $perPage = 15): LengthAwarePaginator;
}
