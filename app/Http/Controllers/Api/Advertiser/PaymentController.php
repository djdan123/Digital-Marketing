<?php

namespace App\Http\Controllers\Api\Advertiser;

use App\Http\Controllers\Controller;
use App\Http\Ressources\PaymentResource;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $payments = Payment::where('advertiser_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate($request->query('per_page', 15));

        return response()->json(['data' => PaymentResource::collection($payments)]);
    }

    public function show(Payment $payment): PaymentResource
    {
        abort_unless($payment->advertiser_id === auth()->id(), 403);

        return new PaymentResource($payment);
    }
}