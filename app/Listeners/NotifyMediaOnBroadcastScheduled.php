<?php

namespace App\Listeners;

use App\Events\BroadcastScheduled;
use App\Notifications\Broadcast\BroadcastScheduledNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifyMediaOnBroadcastScheduled implements ShouldQueue
{
    public function handle(BroadcastScheduled $event): void
    {
        $broadcast = $event->broadcast->loadMissing('media');

        $recipient = config('mail.from.address');

        if ($recipient) {
            Notification::route('mail', $recipient)
                ->notify(new BroadcastScheduledNotification($broadcast));
        }
    }
}
