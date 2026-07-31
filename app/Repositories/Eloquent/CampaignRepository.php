<?php

namespace App\Repositories\Eloquent;

use App\Models\Campaign;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CampaignRepository extends BaseRepository implements \App\Repositories\Contracts\CampaignRepositoryInterface
{
    public function __construct(Campaign $model)
    {
        parent::__construct($model);
    }

    public function findByAdvertiser(int $advertiserId, array $relations = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->query()
            ->with($relations)
            ->where('advertiser_id', $advertiserId)
            ->orderBy('starts_at', 'desc')
            ->paginate($perPage);
    }

    public function findActiveByAdvertiser(int $advertiserId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->query()
            ->where('advertiser_id', $advertiserId)
            ->where('status', 'active')
            ->orderBy('starts_at', 'desc')
            ->paginate($perPage);
    }
}
