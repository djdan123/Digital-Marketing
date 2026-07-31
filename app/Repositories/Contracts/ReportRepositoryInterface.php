<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ReportRepositoryInterface extends RepositoryInterface
{
    public function findByAdvertiser(int $advertiserId, int $perPage = 15): LengthAwarePaginator;
    public function findByCampaign(int $campaignId, int $perPage = 15): LengthAwarePaginator;
}