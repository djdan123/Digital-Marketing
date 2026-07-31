<?php

namespace App\Listeners;

use App\Events\BroadcastScheduled;
use App\Models\Statistic;
use App\Models\Broadcast;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateStatisticsListener implements ShouldQueue
{
    public function handle(BroadcastScheduled $event): void
    {
        $broadcast = $event->broadcast;

        // Increment scheduled broadcasts counter (simple example)
        Statistic::create([
            'statisticable_type' => Broadcast::class,
            'statisticable_id' => $broadcast->id,
            'impressions' => 0,
            'metadata' => ['event' => 'broadcast_scheduled'],
            'date' => now()->toDateString(),
        ]);
    }
}
