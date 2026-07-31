<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessPaymentWebhookJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();

        // Dispatch job to process webhook asynchronously
        ProcessPaymentWebhookJob::dispatch($payload);

        return response()->json(['message' => 'Webhook reçu'], 202);
    }
}
