<?php

namespace App\Repositories\Eloquent;

use App\Models\Media;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MediaRepository extends BaseRepository implements \App\Repositories\Contracts\MediaRepositoryInterface
{
    public function __construct(Media $model)
    {
        parent::__construct($model);
    }

    public function findActiveByType(string $type, int $perPage = 15): LengthAwarePaginator
    {
        return $this->query()
            ->where('type', $type)
            ->where('status', 'active')
            ->orderBy('name')
            ->paginate($perPage);
    }
}
