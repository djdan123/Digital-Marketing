<?php

namespace App\Services\Contracts;

use App\Models\Report;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ReportServiceInterface extends ServiceInterface
{
    /**
     * Génère un rapport à partir de filtres.
     */
    public function generate(array $filters, string $type): Report;

    /**
     * Récupère les rapports d'un annonceur.
     */
    public function getForAdvertiser(int $advertiserId, int $perPage = 15): LengthAwarePaginator;
}