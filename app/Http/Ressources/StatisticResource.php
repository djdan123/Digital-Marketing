<?php

namespace App\Http\Ressources;

use Illuminate\Http\Resources\Json\JsonResource;

class StatisticResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'entity_type' => $this->entity_type,
            'entity_id' => $this->entity_id,
            'views' => $this->views,
            'clicks' => $this->clicks,
            'conversions' => $this->conversions,
            'revenue' => $this->revenue,
            'date' => $this->date?->toDateString(),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
