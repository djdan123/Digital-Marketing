<?php

namespace App\Services\Media;

class CommissionService
{
    public function calculateCommission(float $amount): float
    {
        return round($amount * 0.15, 4);
    }

    public function calculateMediaPayout(float $amount): float
    {
        return round($amount * 0.85, 4);
    }

    public function calculateCost(string $contentType, ?float $duration = 0, int $broadcastsCount = 1): float
    {
        $broadcastsCount = max(1, $broadcastsCount);
        $duration = max(0, $duration ?? 0);

        return match ($contentType) {
            'image' => 500 * $broadcastsCount,
            'text' => 2000 * $broadcastsCount,
            'audio' => 10000 * $duration * $broadcastsCount,
            'video' => 50000 * $duration * $broadcastsCount,
            default => 0,
        };
    }
}
