<?php

namespace App\Services\Contracts;

use App\DTOs\Campaign\CreateCampaignDTO;
use App\DTOs\Campaign\UpdateCampaignDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Campaign;

interface CampaignServiceInterface extends ServiceInterface
{
    public function create(CreateCampaignDTO $dto): Campaign;

    public function update(Campaign $campaign, UpdateCampaignDTO $dto): Campaign;

    public function findByAdvertiser(int $advertiserId, int $perPage = 15): LengthAwarePaginator;

    public function findActiveByAdvertiser(int $advertiserId, int $perPage = 15): LengthAwarePaginator;
}
