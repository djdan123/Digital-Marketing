<?php

namespace App\Repositories\Eloquent;

use App\Models\Broadcast;
use App\Repositories\Contracts\BroadcastRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BroadcastRepository extends BaseRepository implements BroadcastRepositoryInterface
{
    public function __construct(Broadcast $model)
    {
        parent::__construct($model);
    }

    public function findBySchedule(int $scheduleId, array $relations = []): ?Broadcast
    {
        return $this->query()
            ->with($relations)
            ->where('schedule_id', $scheduleId)
            ->first();
    }

    public function findByMedia(int $mediaId, array $relations = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->query()
            ->with($relations)
            ->where('media_id', $mediaId)
            ->orderBy('broadcasted_at', 'desc')
            ->paginate($perPage);
    }

    public function findPending(array $relations = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->query()
            ->with($relations)
            ->where('status', 'scheduled')
            ->where('broadcasted_at', '>=', now())
            ->orderBy('broadcasted_at')
            ->paginate($perPage);
    }
}