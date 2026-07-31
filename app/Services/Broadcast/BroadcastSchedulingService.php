<?php

namespace App\Services\Broadcast;

use App\DTOs\Schedule\CreateScheduleDTO;
use App\Events\BroadcastScheduled;
use App\Models\Broadcast;
use App\Models\Schedule;
use App\Repositories\Contracts\ScheduleRepositoryInterface;
use Illuminate\Support\Facades\DB;

class BroadcastSchedulingService
{
    public function __construct(
        private ScheduleRepositoryInterface $scheduleRepository
    ) {}

    /**
     * Réserve un créneau de diffusion (vérifie les conflits)
     */
    public function reserve(CreateScheduleDTO $dto): Schedule
    {
        // Vérification de disponibilité (simplifiée)
        $conflict = $this->scheduleRepository->findConflicting(
            $dto->media_id,
            $dto->broadcast_date,
            $dto->start_time,
            $dto->end_time
        );

        if ($conflict) {
            throw new \Exception('Ce créneau est déjà réservé pour ce média.');
        }

        return DB::transaction(function () use ($dto) {
            $schedule = $this->scheduleRepository->create([
                'campaign_id'       => $dto->campaign_id,
                'advertisement_id'  => $dto->advertisement_id,
                'media_id'          => $dto->media_id,
                'broadcast_date'    => $dto->broadcast_date,
                'start_time'        => $dto->start_time,
                'end_time'          => $dto->end_time,
                'frequency'         => $dto->frequency,
                'total_spots'       => $dto->total_spots,
                'price_per_spot'    => $dto->price_per_spot,
                'total_price'       => $dto->total_price,
                'status'            => 'pending',
            ]);

            // Créer une entrée Broadcast pour le suivi
            $broadcast = Broadcast::create([
                'schedule_id'    => $schedule->id,
                'media_id'       => $dto->media_id,
                'advertisement_id'=> $dto->advertisement_id,
                'broadcasted_at' => null,
                'status'         => 'scheduled',
                'notes'          => 'Réservation initiale',
            ]);

            event(new BroadcastScheduled($broadcast));

            return $schedule;
        });
    }

    public function confirmSchedule(Schedule $schedule): void
    {
        $schedule->update(['status' => 'scheduled']);
        // Mettre à jour le broadcast associé
        $schedule->broadcast()->update(['status' => 'scheduled']);
    }

    public function markAsBroadcasted(Schedule $schedule): void
    {
        $schedule->update(['status' => 'broadcasted']);
        $schedule->broadcast()->update([
            'status'         => 'broadcasted',
            'broadcasted_at' => now(),
        ]);
    }
}