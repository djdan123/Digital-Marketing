<?php

namespace App\Services\Contracts;

use App\Models\Campaign;
use App\Models\Media;

interface CampaignPricingServiceInterface extends ServiceInterface
{
    /**
     * Calcule le coût total d'une campagne en fonction des tarifs configurés.
     */
    public function calculateCost(Campaign $campaign, Media $media, array $scheduleData): float;

    /**
     * Applique un coupon de réduction sur un montant.
     */
    public function applyCoupon(float $amount, string $couponCode): float;
}