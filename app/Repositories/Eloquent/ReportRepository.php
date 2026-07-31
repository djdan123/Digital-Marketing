<?php

namespace App\Repositories\Eloquent;

use App\Models\Report;
use App\Repositories\Contracts\ReportRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ReportRepository extends BaseRepository implements ReportRepositoryInterface
{
    public function __construct(Report $model)
    {
        parent::__construct($model);
    }

    public function findByAdvertiser(int $advertiserId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->query()
            ->where('advertiser_id', $advertiserId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function findByCampaign(int $campaignId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->query()
            ->where('campaign_id', $campaignId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}