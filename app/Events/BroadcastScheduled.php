<?php

namespace App\Events;

use App\Models\Broadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BroadcastScheduled
{
    use Dispatchable, SerializesModels;

    public function __construct(public Broadcast $broadcast)
    {
    }
}
