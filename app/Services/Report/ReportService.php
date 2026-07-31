<?php

namespace App\Services\Report;

use App\Models\Report;
use App\Repositories\Contracts\ReportRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class ReportService
{
    public function __construct(
        private ReportRepositoryInterface $reportRepository
    ) {}

    /**
     * Génère un rapport à partir de filtres.
     */
    public function generate(array $filters, string $type): Report
    {
        // Récupérer les données selon les filtres
        $results = $this->collectData($filters, $type);

        $report = $this->reportRepository->create([
            'advertiser_id' => $filters['advertiser_id'] ?? null,
            'campaign_id'   => $filters['campaign_id'] ?? null,
            'title'         => $filters['title'] ?? 'Rapport ' . now()->toDateString(),
            'description'   => $filters['description'] ?? null,
            'filters'       => $filters,
            'results'       => $results,
            'type'          => $type,
        ]);

        // Génération d'un fichier (excel ou pdf)
        $path = $this->exportToFile($report, $type);
        $report->update(['file_path' => $path]);

        return $report;
    }

    private function collectData(array $filters, string $type): array
    {
        // Logique métier – exemples
        $data = [
            'total_impressions' => rand(1000, 50000),
            'total_clicks'      => rand(100, 5000),
            'total_cost'        => rand(100, 5000),
            'ctr'               => rand(1, 20) / 10,
        ];
        return $data;
    }

    private function exportToFile(Report $report, string $type): string
    {
        $fileName = 'reports/report_' . $report->id . '.' . ($type === 'excel' ? 'xlsx' : 'pdf');
        // Simulation de l'export
        // Storage::disk('local')->put($fileName, 'contenu du rapport');
        return $fileName;
    }

    public function getForAdvertiser(int $advertiserId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->reportRepository->findByAdvertiser($advertiserId, $perPage);
    }
}