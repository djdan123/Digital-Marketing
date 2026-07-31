<?php

namespace App\Services\Contracts;

use App\DTOs\Advertisement\UploadAdvertisementDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Advertisement;

interface AdvertisementServiceInterface extends ServiceInterface
{
    public function create(UploadAdvertisementDTO $dto): Advertisement;

    public function update(Advertisement $advertisement, UploadAdvertisementDTO $dto): Advertisement;

    public function findByCampaign(int $campaignId, int $perPage = 15): LengthAwarePaginator;

    public function findPendingByCampaign(int $campaignId, int $perPage = 15): LengthAwarePaginator;

    public function approve(Advertisement $advertisement, ?string $comments = null): Advertisement;

    public function reject(Advertisement $advertisement, ?string $comments = null): Advertisement;
}
