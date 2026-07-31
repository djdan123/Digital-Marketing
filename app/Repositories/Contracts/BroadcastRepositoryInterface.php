<?php

namespace App\Repositories\Contracts;

interface BroadcastRepositoryInterface extends RepositoryInterface
{
    public function findBySchedule(int $scheduleId, array $relations = []): ?\App\Models\Broadcast;
    public function findByMedia(int $mediaId, array $relations = [], int $perPage = 15);
    public function findPending(array $relations = [], int $perPage = 15);
}