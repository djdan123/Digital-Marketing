<?php

namespace App\Http\Ressources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdvertisementResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'campaign_id' => $this->campaign_id,
            'media_id' => $this->media_id,
            'title' => $this->title,
            'description' => $this->description,
            'format' => $this->format,
            'status' => $this->status,
            'meta' => $this->meta,
            'file_path' => data_get($this->meta, 'file_path'),
            'file_url' => data_get($this->meta, 'file_url'),
            'filename' => data_get($this->meta, 'filename'),
            'mime' => data_get($this->meta, 'mime'),
            'uploaded_type' => data_get($this->meta, 'type'),
            'size' => data_get($this->meta, 'size'),
            'cost' => $this->cost,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
            'campaign' => $this->whenLoaded('campaign', fn () => new CampaignResource($this->campaign)),
            'media' => $this->whenLoaded('media', fn () => new MediaResource($this->media)),
        ];
    }
}
