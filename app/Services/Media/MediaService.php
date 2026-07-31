<?php

namespace App\Services\Media;

use App\Repositories\Contracts\MediaRepositoryInterface;
use App\Services\Contracts\MediaServiceInterface;
use App\Models\Media;

class MediaService implements MediaServiceInterface
{
    public function __construct(private MediaRepositoryInterface $mediaRepository)
    {
    }

    public function allActiveByType(string $type, int $perPage = 15)
    {
        return $this->mediaRepository->findActiveByType($type, $perPage);
    }

    public function find(int $id): ?Media
    {
        return $this->mediaRepository->find($id);
    }
}
