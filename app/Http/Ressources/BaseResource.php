<?php

namespace App\Http\Ressources;

use Illuminate\Http\Resources\Json\JsonResource;

abstract class BaseResource extends JsonResource
{
    public function toArray($request): array
    {
        return parent::toArray($request);
    }

    public static function collection($resource)
    {
        return parent::collection($resource);
    }
}
