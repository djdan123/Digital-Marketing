<?php

namespace App\Http\Ressources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CampaignCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'count' => $this->collection->count(),
                'total' => $this->total(),
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
                'last_page' => $this->lastPage(),
            ],
        ];
    }
}
