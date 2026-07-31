<?php

namespace App\Listeners;

use App\Events\AdvertisementRejected;
use App\Notifications\Advertisement\AdvertisementRejectedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifyAdvertiserOnRejection implements ShouldQueue
{
    public function handle(AdvertisementRejected $event): void
    {
        $advertisement = $event->advertisement->loadMissing('campaign.advertiser');

        $advertiser = $advertisement->campaign?->advertiser;

        if ($advertiser) {
            Notification::send(
                $advertiser,
                new AdvertisementRejectedNotification($advertisement, $event->comments)
            );
        }
    }
}
