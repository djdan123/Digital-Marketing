<?php

namespace App\DTOs\Campaign;

use App\DTOs\AbstractDTO;

readonly class CreateCampaignDTO extends AbstractDTO
{
    public function __construct(
        public int $advertiser_id,
        public string $name,
        public ?string $objective,
        public string $status,
        public ?string $starts_at,
        public ?string $ends_at,
        public ?string $budget,
        public ?string $spent,
        public ?array $targeting = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            advertiser_id: (int)$data['advertiser_id'],
            name: (string)$data['name'],
            objective: $data['objective'] ?? null,
            status: $data['status'] ?? 'draft',
            starts_at: $data['starts_at'] ?? null,
            ends_at: $data['ends_at'] ?? null,
            budget: isset($data['budget']) ? (string)$data['budget'] : null,
            spent: isset($data['spent']) ? (string)$data['spent'] : null,
            targeting: $data['targeting'] ?? null,
        );
    }
}
