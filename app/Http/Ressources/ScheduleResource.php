<?php

namespace App\Http\Ressources;

use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'campaign_id' => $this->campaign_id,
            'advertisement_id' => $this->advertisement_id,
            'media_id' => $this->media_id,
            'scheduled_at' => $this->scheduled_at?->toDateTimeString(),
            'spots' => $this->spots,
            'price' => $this->price,
            'status' => $this->status,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
