<?php

namespace App\Http\Ressources;

use Illuminate\Http\Resources\Json\JsonResource;

class CampaignResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'advertiser_id' => $this->advertiser_id,
            'name' => $this->name,
            'objective' => $this->objective,
            'status' => $this->status,
            'starts_at' => $this->starts_at?->toDateString(),
            'ends_at' => $this->ends_at?->toDateString(),
            'budget' => $this->budget,
            'spent' => $this->spent,
            'targeting' => $this->targeting,
            'duration_days' => $this->duration_days,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
            'advertisements' => AdvertisementResource::collection($this->whenLoaded('advertisements')),
        ];
    }
}
