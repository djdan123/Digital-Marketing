<?php

namespace App\Events;

use App\Models\Advertisement;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdvertisementApproved
{
    use Dispatchable, SerializesModels;

    public function __construct(public Advertisement $advertisement)
    {
    }
}
