<?php

namespace App\Events;

use App\Models\Advertisement;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdvertisementRejected
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Advertisement $advertisement,
        public ?string $comments = null,
    ) {
    }
}
