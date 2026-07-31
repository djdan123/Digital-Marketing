<?php

namespace App\Http\Ressources;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category_id' => $this->category_id,
            'company_id' => $this->company_id,
            'type' => $this->type,
            'pricing_type' => $this->pricing_type,
            'base_price' => $this->base_price,
            'description' => $this->description,
            'status' => $this->status,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
            'category' => $this->whenLoaded('category', fn () => ['id' => $this->category?->id, 'name' => $this->category?->name]),
            'company' => $this->whenLoaded('company', fn () => ['id' => $this->company?->id, 'name' => $this->company?->name]),
        ];
    }
}
