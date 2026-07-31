<?php

namespace App\Listeners;

use App\Events\AdvertisementApproved;
use App\Models\Broadcast;
use Illuminate\Contracts\Queue\ShouldQueue;

class ScheduleBroadcastListener implements ShouldQueue
{
    public function handle(AdvertisementApproved $event): void
    {
        $ad = $event->advertisement;

        // Simple scheduling: create a single broadcast record for the advertisement
        $broadcast = Broadcast::create([
            'schedule_id' => optional($ad->campaign->schedule)->id,
            'media_id' => $ad->media_id,
            'broadcasted_at' => now()->addDays(1),
            'status' => 'scheduled',
            'notes' => 'Scheduled after approval',
        ]);

        event(new \App\Events\BroadcastScheduled($broadcast));
    }
}
