<?php

namespace App\Services\Statistic;

use App\Models\Campaign;
use App\Models\Schedule;
use App\Models\Statistic;
use App\Repositories\Contracts\StatisticRepositoryInterface;
use Carbon\Carbon;

class StatisticAggregationService
{
    public function __construct(
        private StatisticRepositoryInterface $statisticRepository
    ) {}

    /**
     * Agrège les statistiques d'une campagne pour une date donnée.
     */
    public function aggregateForCampaign(Campaign $campaign, Carbon $date): void
    {
        $schedules = $campaign->schedules()
            ->whereDate('broadcast_date', $date)
            ->where('status', 'broadcasted')
            ->get();

        $impressions = $schedules->sum('impressions') ?? 0;
        $clicks = $schedules->sum('clicks') ?? 0;
        $views = $schedules->sum('views') ?? 0;

        $stat = $this->statisticRepository->updateOrCreate(
            [
                'campaign_id' => $campaign->id,
                'date'        => $date->toDateString(),
            ],
            [
                'impressions' => $impressions,
                'clicks'      => $clicks,
                'views'       => $views,
                'ctr'         => $impressions > 0 ? ($clicks / $impressions) * 100 : 0,
                'cpm'         => $impressions > 0 ? ($campaign->budget / $impressions) * 1000 : 0,
                'cost'        => $campaign->spent,
                'reach'       => $views,
            ]
        );
    }

    /**
     * Agrège pour toutes les campagnes actives du jour.
     */
    public function aggregateToday(): void
    {
        $today = Carbon::today();
        $campaigns = Campaign::where('status', 'active')->get();
        foreach ($campaigns as $campaign) {
            $this->aggregateForCampaign($campaign, $today);
        }
    }

    /**
     * Génère un rapport de stats pour un annonceur.
     */
    public function getStatsForAdvertiser(int $advertiserId, ?string $dateFrom = null, ?string $dateTo = null): array
    {
        // Logique de filtrage selon l'annonceur
        $query = Statistic::whereHas('campaign', fn($q) => $q->where('advertiser_id', $advertiserId));
        if ($dateFrom) $query->where('date', '>=', $dateFrom);
        if ($dateTo) $query->where('date', '<=', $dateTo);
        return $query->get()->toArray();
    }
}