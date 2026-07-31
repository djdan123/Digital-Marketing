<?php

namespace App\DTOs\Campaign;

use App\DTOs\AbstractDTO;

readonly class UpdateCampaignDTO extends AbstractDTO
{
    public function __construct(
        public ?string $name = null,
        public ?string $objective = null,
        public ?string $status = null,
        public ?string $starts_at = null,
        public ?string $ends_at = null,
        public ?string $budget = null,
        public ?string $spent = null,
        public ?array $targeting = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            objective: $data['objective'] ?? null,
            status: $data['status'] ?? null,
            starts_at: $data['starts_at'] ?? null,
            ends_at: $data['ends_at'] ?? null,
            budget: isset($data['budget']) ? (string)$data['budget'] : null,
            spent: isset($data['spent']) ? (string)$data['spent'] : null,
            targeting: $data['targeting'] ?? null,
        );
    }
}
