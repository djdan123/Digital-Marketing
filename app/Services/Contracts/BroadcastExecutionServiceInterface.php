<?php

namespace App\Services\Contracts;

use App\Models\Broadcast;
use Illuminate\Http\UploadedFile;

interface BroadcastExecutionServiceInterface extends ServiceInterface
{
    /**
     * Lance une diffusion (statut "in_progress").
     */
    public function startBroadcast(Broadcast $broadcast): void;

    /**
     * Marque une diffusion comme terminée avec option d'upload d'une preuve.
     */
    public function completeBroadcast(Broadcast $broadcast, ?string $proofPath = null): void;

    /**
     * Marque une diffusion comme échouée avec un motif.
     */
    public function failBroadcast(Broadcast $broadcast, string $reason): void;

    /**
     * Téléverse une preuve de diffusion.
     * @return string Chemin du fichier stocké
     */
    public function uploadProof(Broadcast $broadcast, UploadedFile $file): string;
}