<?php

namespace App\Providers;

use App\Events\AdvertisementApproved;
use App\Events\AdvertisementRejected;
use App\Events\BroadcastScheduled;
use App\Events\PaymentFailed;
use App\Events\PaymentSucceeded;
use App\Listeners\GenerateInvoice;
use App\Listeners\NotifyAdvertiserOnApproval;
use App\Listeners\NotifyAdvertiserOnRejection;
use App\Listeners\NotifyMediaOnBroadcastScheduled;
use App\Listeners\ScheduleBroadcastListener;
use App\Listeners\SendPaymentFailedNotification;
use App\Listeners\SendPaymentNotification;
use App\Listeners\UpdateStatisticsListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PaymentSucceeded::class => [
            SendPaymentNotification::class,
            GenerateInvoice::class,
        ],
        PaymentFailed::class => [
            SendPaymentFailedNotification::class,
        ],
        AdvertisementApproved::class => [
            NotifyAdvertiserOnApproval::class,
            ScheduleBroadcastListener::class,
        ],
        AdvertisementRejected::class => [
            NotifyAdvertiserOnRejection::class,
        ],
        BroadcastScheduled::class => [
            UpdateStatisticsListener::class,
            NotifyMediaOnBroadcastScheduled::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}
