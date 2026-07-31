<?php

namespace App\DTOs\Advertisement;

use App\DTOs\AbstractDTO;

readonly class UploadAdvertisementDTO extends AbstractDTO
{
    public function __construct(
        public int $campaign_id,
        public int $media_id,
        public string $title,
        public ?string $description,
        public ?string $format,
        public string $status,
        public ?array $meta = null,
        public ?string $cost = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            campaign_id: (int)$data['campaign_id'],
            media_id: (int)$data['media_id'],
            title: (string)$data['title'],
            description: $data['description'] ?? null,
            format: $data['format'] ?? null,
            status: $data['status'] ?? 'draft',
            meta: $data['meta'] ?? null,
            cost: isset($data['cost']) ? (string)$data['cost'] : null,
        );
    }
}
