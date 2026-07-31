<?php

namespace App\Repositories\Eloquent;

use App\Models\Advertisement;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AdvertisementRepository extends BaseRepository implements \App\Repositories\Contracts\AdvertisementRepositoryInterface
{
    public function __construct(Advertisement $model)
    {
        parent::__construct($model);
    }

    public function findByCampaign(int $campaignId, array $relations = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->query()
            ->with($relations)
            ->where('campaign_id', $campaignId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function findPendingByCampaign(int $campaignId, array $relations = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->query()
            ->with($relations)
            ->where('campaign_id', $campaignId)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}
