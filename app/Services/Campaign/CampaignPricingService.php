<?php

namespace App\Services\Campaign;

use App\Models\Campaign;
use App\Models\Pricing;
use App\Models\Media;
use Carbon\Carbon;

class CampaignPricingService
{
    /**
     * Calcule le coût total d'une campagne en fonction des tarifs configurés.
     */
    public function calculateCost(Campaign $campaign, Media $media, array $scheduleData): float
    {
        // Récupère le tarif applicable pour ce média et cette période
        $pricing = Pricing::where('media_id', $media->id)
            ->where('valid_from', '<=', $scheduleData['broadcast_date'])
            ->where(function ($q) use ($scheduleData) {
                $q->where('valid_until', '>=', $scheduleData['broadcast_date'])
                  ->orWhereNull('valid_until');
            })
            ->first();

        if (!$pricing) {
            throw new \Exception('Aucun tarif défini pour ce média à cette date.');
        }

        $unitPrice = $pricing->price;
        $spots = $scheduleData['total_spots'] ?? 1;

        // Calcul selon l'unité
        $total = match ($pricing->unit) {
            'per_spot'    => $unitPrice * $spots,
            'per_minute'  => $unitPrice * ($scheduleData['duration_minutes'] ?? 1),
            'per_second'  => $unitPrice * ($scheduleData['duration_seconds'] ?? 60),
            'per_day'     => $unitPrice,
            'per_week'    => $unitPrice,
            'per_month'   => $unitPrice,
            default       => $unitPrice * $spots,
        };

        // Appliquer taxes et remises
        if ($pricing->tax_rate > 0) {
            $total += $total * ($pricing->tax_rate / 100);
        }

        return $total;
    }

    /**
     * Applique un coupon de réduction.
     */
    public function applyCoupon(float $amount, string $couponCode): float
    {
        // Logique à implémenter avec le modèle Coupon
        return $amount; // placeholder
    }
}