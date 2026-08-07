<?php

namespace App\Http\Controllers\Api\Media;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\Schedule;
use App\Services\Media\CommissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class RequestController extends Controller
{
    public function __construct(private CommissionService $commissionService)
    {
    }
    public function index(Request $request): JsonResponse
    {
        $this->ensureMediaManager($request);

        $mediaId = $request->user()?->media_id;
        $query = Advertisement::query()->where('media_id', $mediaId);

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $requests = $query->with(['campaign.advertiser', 'media'])
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (Advertisement $advertisement) => $this->formatRequest($advertisement));

        return response()->json(['data' => $requests]);
    }

    public function show(Request $request, Advertisement $advertisement): JsonResponse
    {
        $this->ensureMediaManager($request);

        if ($advertisement->media_id !== $request->user()?->media_id) {
            abort(403, 'Cette demande ne vous concerne pas.');
        }

        return response()->json(['data' => $this->formatRequest($advertisement->load(['campaign.advertiser', 'media']))]);
    }

    public function approve(Request $request, Advertisement $advertisement): JsonResponse
    {
        $this->ensureMediaManager($request);

        if ($advertisement->media_id !== $request->user()?->media_id) {
            abort(403, 'Cette demande ne vous concerne pas.');
        }

        $request->validate([
            'start_at' => ['required', 'date'],
            'end_at' => ['required', 'date'],
        ]);

        $meta = $advertisement->meta ?? [];
        $meta['approved_at'] = now()->toISOString();
        $meta['schedule_start_at'] = $request->input('start_at');
        $meta['schedule_end_at'] = $request->input('end_at');

        $cost = $advertisement->cost ?: $this->commissionService->calculateCost(
            $advertisement->format,
            $meta['duration'] ?? 0,
            $meta['broadcasts_count'] ?? 1
        );

        $advertisement->status = 'accepted';
        $advertisement->cost = $cost;
        $advertisement->meta = $meta;
        $advertisement->save();

        Schedule::create([
            'campaign_id' => $advertisement->campaign_id,
            'advertisement_id' => $advertisement->id,
            'media_id' => $advertisement->media_id,
            'scheduled_at' => $request->input('start_at'),
            'spots' => $meta['broadcasts_count'] ?? 1,
            'price' => $cost,
            'status' => 'confirmed',
        ]);

        $advertisement->load(['campaign.advertiser', 'media']);

        return response()->json([
            'message' => 'Demande acceptée.',
            'data' => $this->formatRequest($advertisement),
            'broadcast_scheduled' => true,
            'commission' => $this->commissionService->calculateCommission($cost),
            'media_payout' => $this->commissionService->calculateMediaPayout($cost),
        ]);
    }

    public function reject(Request $request, Advertisement $advertisement): JsonResponse
    {
        $this->ensureMediaManager($request);

        if ($advertisement->media_id !== $request->user()?->media_id) {
            abort(403, 'Cette demande ne vous concerne pas.');
        }

        $request->validate([
            'reason' => ['required', 'string', 'min:3'],
        ]);

        $meta = $advertisement->meta ?? [];
        $meta['rejection_reason'] = $request->input('reason');

        $advertisement->status = 'rejected';
        $advertisement->meta = $meta;
        $advertisement->save();

        $advertisement->load(['campaign.advertiser', 'media']);

        return response()->json(['message' => 'Demande refusée.', 'data' => $this->formatRequest($advertisement)]);
    }

    public function message(Request $request, Advertisement $advertisement): JsonResponse
    {
        $this->ensureMediaManager($request);

        if ($advertisement->media_id !== $request->user()?->media_id) {
            abort(403, 'Cette demande ne vous concerne pas.');
        }

        $request->validate([
            'message' => ['required', 'string', 'min:1'],
        ]);

        $meta = $advertisement->meta ?? [];
        $messages = Arr::wrap($meta['messages'] ?? []);
        $messages[] = [
            'sender' => 'media_manager',
            'body' => $request->input('message'),
            'created_at' => now()->toISOString(),
        ];

        $meta['messages'] = $messages;
        if ($request->input('action') === 'revision') {
            $advertisement->status = 'in_discussion';
        }

        $advertisement->meta = $meta;
        $advertisement->save();

        return response()->json(['message' => 'Message envoyé.', 'data' => $this->formatRequest($advertisement)]);
    }

    protected function ensureMediaManager(Request $request): void
    {
        if ($request->user()?->role !== 'media_manager') {
            abort(403, 'Accès réservé au media manager.');
        }
    }

    protected function formatRequest(Advertisement $advertisement): array
    {
        $meta = $advertisement->meta ?? [];
        $advertiser = $advertisement->campaign?->advertiser;
        $cost = $advertisement->cost ?: $this->commissionService->calculateCost(
            $advertisement->format,
            $meta['duration'] ?? 0,
            $meta['broadcasts_count'] ?? 1
        );

        return [
            'id' => $advertisement->id,
            'title' => $advertisement->title,
            'campaign' => [
                'name' => $advertisement->campaign?->name ?? 'Campagne',
            ],
            'advertiser' => [
                'name' => $advertiser ? trim(($advertiser->first_name ?? '') . ' ' . ($advertiser->last_name ?? '')) : 'Annonceur',
            ],
            'media' => [
                'name' => $advertisement->media?->name ?? 'Média',
            ],
            'content_type' => $advertisement->format,
            'duration' => $meta['duration'] ?? null,
            'broadcasts_count' => $meta['broadcasts_count'] ?? 1,
            'total_amount' => (float) $cost,
            'commission' => (float) $this->commissionService->calculateCommission($cost),
            'media_payout' => (float) $this->commissionService->calculateMediaPayout($cost),
            'requested_at' => $advertisement->created_at->toISOString(),
            'start_date' => $meta['start_date'] ?? $meta['schedule_start_at'] ?? null,
            'end_date' => $meta['end_date'] ?? $meta['schedule_end_at'] ?? null,
            'status' => $advertisement->status,
            'message' => $advertisement->description,
            'initial_message' => $meta['initial_message'] ?? $advertisement->description,
            'messages' => Arr::wrap($meta['messages'] ?? []),
            'advertisement' => [
                'format' => $advertisement->format,
                'file_path' => $meta['file_path'] ?? null,
            ],
            'meta' => $meta,
        ];
    }
}
