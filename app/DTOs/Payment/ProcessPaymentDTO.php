<?php

namespace App\DTOs\Payment;

use App\DTOs\AbstractDTO;

readonly class ProcessPaymentDTO extends AbstractDTO
{
    public function __construct(
        public int $advertiser_id,
        public int $campaign_id,
        public string $amount,
        public string $currency,
        public string $payment_method,
        public string $status,
        public ?string $reference = null,
        public ?array $metadata = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            advertiser_id: (int)$data['advertiser_id'],
            campaign_id: (int)$data['campaign_id'],
            amount: (string)$data['amount'],
            currency: (string)$data['currency'],
            payment_method: (string)$data['payment_method'],
            status: $data['status'] ?? 'pending',
            reference: $data['reference'] ?? null,
            metadata: $data['metadata'] ?? null,
        );
    }
}
