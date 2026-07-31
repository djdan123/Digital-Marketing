<?php

namespace App\Repositories\Contracts;

interface ScheduleRepositoryInterface extends RepositoryInterface
{
    public function findConflicting(int $mediaId, string $date, string $startTime, string $endTime): ?\App\Models\Schedule;
    public function findByCampaign(int $campaignId, array $relations = [], int $perPage = 15);
    public function findByMedia(int $mediaId, array $relations = [], int $perPage = 15);
}