<?php

namespace App\Http\Controllers\Api\Advertiser;

use App\Http\Controllers\Controller;
use App\Http\Ressources\PaymentResource;
use App\Models\Advertiser;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Enums\PaymentMethod;

class SubscriptionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $advertiser = $this->resolveAdvertiser($request);

        if (!$advertiser) {
            return response()->json(['message' => 'Profil annonceur introuvable.'], 404);
        }

        $subscriptions = Payment::where('advertiser_id', $advertiser->id)
            ->where('metadata->request_type', 'subscription')
            ->orderByDesc('created_at')
            ->paginate($request->query('per_page', 15));

        return response()->json(['data' => PaymentResource::collection($subscriptions)]);
    }

    public function store(Request $request): PaymentResource
    {
        $advertiser = $this->resolveAdvertiser($request);

        if (!$advertiser) {
            abort(404, 'Profil annonceur introuvable.');
        }

        $data = $request->validate([
            'media_id' => ['required', 'integer', 'exists:media,id'],
            'plan_name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
            'payment_method' => ['sometimes', 'string', Rule::in(array_map(fn ($value) => $value->value, PaymentMethod::cases()))],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $payment = Payment::create([
            'advertiser_id' => $advertiser->id,
            'campaign_id' => null,
            'amount' => $data['amount'],
            'currency' => strtoupper($data['currency']),
            'payment_method' => $data['payment_method'] ?? 'manual',
            'status' => 'pending',
            'reference' => null,
            'metadata' => [
                'request_type' => 'subscription',
                'media_id' => $data['media_id'],
                'plan_name' => $data['plan_name'],
                'starts_at' => $data['starts_at'],
                'ends_at' => $data['ends_at'] ?? null,
                'notes' => $data['notes'] ?? null,
            ],
        ]);

        return new PaymentResource($payment);
    }

    public function show(Request $request, Payment $subscription): PaymentResource
    {
        $advertiser = $this->resolveAdvertiser($request);

        if (!$advertiser) {
            abort(404, 'Profil annonceur introuvable.');
        }

        abort_unless($subscription->advertiser_id === $advertiser->id, 403);
        abort_unless(data_get($subscription->metadata, 'request_type') === 'subscription', 404);

        return new PaymentResource($subscription);
    }

    private function resolveAdvertiser(Request $request): ?Advertiser
    {
        $user = $request->user();
        if (!$user) {
            return null;
        }

        return Advertiser::where('user_id', $user->id)->first()
            ?? Advertiser::where('email', $user->email)->first();
    }
}
