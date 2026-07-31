<?php

namespace App\Services\Campaign;

use App\DTOs\Campaign\CreateCampaignDTO;
use App\DTOs\Campaign\UpdateCampaignDTO;
use App\Models\Campaign;
use App\Repositories\Contracts\CampaignRepositoryInterface;
use App\Services\Contracts\CampaignServiceInterface;

class CampaignService implements CampaignServiceInterface
{
    public function __construct(private CampaignRepositoryInterface $campaignRepository)
    {
    }

    public function create(CreateCampaignDTO $dto): Campaign
    {
        $data = [
            'advertiser_id' => $dto->advertiser_id,
            'name' => $dto->name,
            'objective' => $dto->objective,
            'status' => $dto->status,
            'starts_at' => $dto->starts_at,
            'ends_at' => $dto->ends_at,
            'budget' => $dto->budget,
            'spent' => $dto->spent,
            'targeting' => $dto->targeting,
        ];

        return $this->campaignRepository->create($data);
    }

    public function update(Campaign $campaign, UpdateCampaignDTO $dto): Campaign
    {
        $data = array_filter([
            'name' => $dto->name,
            'objective' => $dto->objective,
            'status' => $dto->status,
            'starts_at' => $dto->starts_at,
            'ends_at' => $dto->ends_at,
            'budget' => $dto->budget,
            'spent' => $dto->spent,
            'targeting' => $dto->targeting,
        ], fn ($value) => $value !== null);

        return $this->campaignRepository->update($campaign, $data);
    }

    public function findByAdvertiser(int $advertiserId, int $perPage = 15)
    {
        return $this->campaignRepository->findByAdvertiser($advertiserId, [], $perPage);
    }

    public function findActiveByAdvertiser(int $advertiserId, int $perPage = 15)
    {
        return $this->campaignRepository->findActiveByAdvertiser($advertiserId, $perPage);
    }
}
