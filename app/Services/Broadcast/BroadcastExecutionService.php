<?php

namespace App\Services\Broadcast;

use App\Models\Broadcast;
use App\Models\Schedule;
use App\Repositories\Contracts\BroadcastRepositoryInterface;

class BroadcastExecutionService
{
    public function __construct(
        private BroadcastRepositoryInterface $broadcastRepository
    ) {}

    /**
     * Exécute une diffusion (passage à l'état "en cours").
     */
    public function startBroadcast(Broadcast $broadcast): void
    {
        $broadcast->update([
            'status'        => 'in_progress',
            'broadcasted_at'=> now(),
        ]);
    }

    /**
     * Marque une diffusion comme terminée.
     */
    public function completeBroadcast(Broadcast $broadcast, ?string $proofPath = null): void
    {
        $broadcast->update([
            'status'          => 'completed',
            'proof_file_path' => $proofPath ?? $broadcast->proof_file_path,
        ]);

        // Mettre à jour le schedule associé
        if ($broadcast->schedule) {
            $broadcast->schedule->update(['status' => 'broadcasted']);
        }
    }

    /**
     * Marque une diffusion comme échouée.
     */
    public function failBroadcast(Broadcast $broadcast, string $reason): void
    {
        $broadcast->update([
            'status' => 'failed',
            'notes'  => $reason,
        ]);
    }

    /**
     * Upload d'une preuve de diffusion par le journaliste.
     */
    public function uploadProof(Broadcast $broadcast, \Illuminate\Http\UploadedFile $file): string
    {
        $path = $file->store('proofs', 'public');
        $broadcast->update(['proof_file_path' => $path]);
        return $path;
    }
}