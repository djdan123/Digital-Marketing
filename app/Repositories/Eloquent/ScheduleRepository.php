<?php

namespace App\Repositories\Eloquent;

use App\Models\Schedule;
use App\Repositories\Contracts\ScheduleRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ScheduleRepository extends BaseRepository implements ScheduleRepositoryInterface
{
    public function __construct(Schedule $model)
    {
        parent::__construct($model);
    }

    public function findConflicting(int $mediaId, string $date, string $startTime, string $endTime): ?Schedule
    {
        return $this->query()
            ->where('media_id', $mediaId)
            ->where('broadcast_date', $date)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                      ->orWhereBetween('end_time', [$startTime, $endTime])
                      ->orWhere(function ($q) use ($startTime, $endTime) {
                          $q->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                      });
            })
            ->first();
    }

    public function findByCampaign(int $campaignId, array $relations = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->query()
            ->with($relations)
            ->where('campaign_id', $campaignId)
            ->orderBy('broadcast_date')
            ->paginate($perPage);
    }

    public function findByMedia(int $mediaId, array $relations = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->query()
            ->with($relations)
            ->where('media_id', $mediaId)
            ->orderBy('broadcast_date')
            ->paginate($perPage);
    }
}