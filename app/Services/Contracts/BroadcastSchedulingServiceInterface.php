<?php

namespace App\Services\Contracts;

use App\DTOs\Schedule\CreateScheduleDTO;
use App\Models\Schedule;

interface BroadcastSchedulingServiceInterface extends ServiceInterface
{
    /**
     * Réserve un créneau de diffusion (vérifie les conflits).
     * @throws \Exception
     */
    public function reserve(CreateScheduleDTO $dto): Schedule;

    /**
     * Confirme un schedule (passage en statut "scheduled").
     */
    public function confirmSchedule(Schedule $schedule): void;

    /**
     * Marque un schedule comme diffusé.
     */
    public function markAsBroadcasted(Schedule $schedule): void;
}