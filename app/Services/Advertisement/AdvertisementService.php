<?php

namespace App\Services\Advertisement;

use App\DTOs\Advertisement\UploadAdvertisementDTO;
use App\Events\AdvertisementApproved;
use App\Events\AdvertisementRejected;
use App\Models\Advertisement;
use App\Repositories\Contracts\AdvertisementRepositoryInterface;
use App\Services\Contracts\AdvertisementServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AdvertisementService implements AdvertisementServiceInterface
{
    public function __construct(
        private AdvertisementRepositoryInterface $advertisementRepository
    ) {}

    public function create(UploadAdvertisementDTO $dto): Advertisement
    {
        $data = [
            'campaign_id' => $dto->campaign_id,
            'media_id'    => $dto->media_id,
            'title'       => $dto->title,
            'description' => $dto->description,
            'format'      => $dto->format,
            'status'      => $dto->status,
            'meta'        => $dto->meta,
            'cost'        => $dto->cost,
        ];

        return $this->advertisementRepository->create($data);
    }

    public function update(Advertisement $advertisement, UploadAdvertisementDTO $dto): Advertisement
    {
        $data = array_filter([
            'campaign_id' => $dto->campaign_id,
            'media_id'    => $dto->media_id,
            'title'       => $dto->title,
            'description' => $dto->description,
            'format'      => $dto->format,
            'status'      => $dto->status,
            'meta'        => $dto->meta,
            'cost'        => $dto->cost,
        ], fn($value) => $value !== null);

        return $this->advertisementRepository->update($advertisement, $data);
    }

    public function findByCampaign(int $campaignId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->advertisementRepository->findByCampaign($campaignId, [], $perPage);
    }

    public function findPendingByCampaign(int $campaignId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->advertisementRepository->findPendingByCampaign($campaignId, [], $perPage);
    }

    public function approve(Advertisement $advertisement, ?string $comments = null): Advertisement
    {
        $advertisement->update(['status' => 'approved']);
        event(new AdvertisementApproved($advertisement));
        return $advertisement;
    }

    public function reject(Advertisement $advertisement, ?string $comments = null): Advertisement
    {
        $advertisement->update(['status' => 'rejected']);
        event(new AdvertisementRejected($advertisement, $comments));
        return $advertisement;
    }
}