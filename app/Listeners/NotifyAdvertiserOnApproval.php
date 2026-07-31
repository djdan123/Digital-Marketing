<?php

namespace App\Listeners;

use App\Events\AdvertisementApproved;
use App\Notifications\Advertisement\AdvertisementApprovedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifyAdvertiserOnApproval implements ShouldQueue
{
    public function handle(AdvertisementApproved $event): void
    {
        $advertisement = $event->advertisement->loadMissing('campaign.advertiser');

        $advertiser = $advertisement->campaign?->advertiser;

        if ($advertiser) {
            Notification::send($advertiser, new AdvertisementApprovedNotification($advertisement));
        }
    }
}
