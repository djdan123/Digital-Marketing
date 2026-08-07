<?php

namespace App\Http\Controllers\Api\Media;

use App\Http\Controllers\Controller;
use App\Http\Ressources\PaymentResource;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class SubscriptionRequestController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->ensureMediaManager($request);

        $mediaId = $request->user()?->media_id;
        $query = Payment::query()
            ->where('status', 'pending')
            ->where('metadata->request_type', 'subscription');

        if ($mediaId) {
            $query->where('metadata->media_id', $mediaId);
        } else {
            $query->whereRaw('1 = 0');
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $payments = $query->orderByDesc('created_at')->get();

        return response()->json(['data' => PaymentResource::collection($payments)]);
    }

    public function show(Request $request, Payment $subscription): PaymentResource
    {
        $this->ensureMediaManager($request);
        $this->ensureOwnership($request, $subscription);
        $this->ensureSubscriptionRequest($subscription);

        return new PaymentResource($subscription);
    }

    public function approve(Request $request, Payment $subscription): JsonResponse
    {
        $this->ensureMediaManager($request);
        $this->ensureOwnership($request, $subscription);
        $this->ensureSubscriptionRequest($subscription);
        abort_unless($subscription->status === 'pending', 400, 'Le paiement doit être en attente.');

        $metadata = Arr::wrap($subscription->metadata);
        $metadata['approved_at'] = now()->toISOString();
        $metadata['approved_by'] = $request->user()?->id;

        $subscription->status = 'completed';
        $subscription->reference = $subscription->reference ?: uniqid('SUB-');
        $subscription->metadata = $metadata;
        $subscription->save();

        Subscription::create([
            'advertiser_id' => $subscription->advertiser_id,
            'plan_name' => data_get($metadata, 'plan_name', 'Abonnement'),
            'price' => $subscription->amount,
            'currency' => $subscription->currency,
            'starts_at' => data_get($metadata, 'starts_at'),
            'ends_at' => data_get($metadata, 'ends_at'),
            'status' => 'active',
        ]);

        return response()->json([
            'message' => 'Abonnement approuvé.',
            'data' => new PaymentResource($subscription),
        ]);
    }

    public function reject(Request $request, Payment $subscription): JsonResponse
    {
        $this->ensureMediaManager($request);
        $this->ensureOwnership($request, $subscription);
        $this->ensureSubscriptionRequest($subscription);

        $data = $request->validate([
            'reason' => ['required', 'string', 'min:3'],
        ]);

        $metadata = Arr::wrap($subscription->metadata);
        $metadata['rejected_at'] = now()->toISOString();
        $metadata['rejected_by'] = $request->user()?->id;
        $metadata['rejection_reason'] = $data['reason'];

        $subscription->status = 'failed';
        $subscription->metadata = $metadata;
        $subscription->save();

        return response()->json([
            'message' => 'Abonnement refusé.',
            'data' => new PaymentResource($subscription),
        ]);
    }

    protected function ensureMediaManager(Request $request): void
    {
        if ($request->user()?->role !== 'media_manager') {
            abort(403, 'Accès réservé au media manager.');
        }
    }

    protected function ensureOwnership(Request $request, Payment $subscription): void
    {
        $mediaId = $request->user()?->media_id;

        if (!$mediaId || data_get($subscription->metadata, 'media_id') != $mediaId) {
            abort(403, 'Cette demande ne vous concerne pas.');
        }
    }

    protected function ensureSubscriptionRequest(Payment $subscription): void
    {
        if (data_get($subscription->metadata, 'request_type') !== 'subscription') {
            abort(404, 'Demande d abonnement non trouvée.');
        }
    }
}
