<?php

namespace App\Services\Contracts;

use App\Models\Campaign;
use Carbon\Carbon;

interface StatisticAggregationServiceInterface extends ServiceInterface
{
    /**
     * Agrège les statistiques d'une campagne pour une date donnée.
     */
    public function aggregateForCampaign(Campaign $campaign, Carbon $date): void;

    /**
     * Agrège pour toutes les campagnes actives du jour.
     */
    public function aggregateToday(): void;

    /**
     * Récupère les stats d'un annonceur (avec filtres de dates optionnels).
     */
    public function getStatsForAdvertiser(int $advertiserId, ?string $dateFrom = null, ?string $dateTo = null): array;
}