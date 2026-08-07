<?php

namespace App\Http\Controllers\Api\Media;

use App\Http\Controllers\Controller;
use App\Models\Broadcast;
use App\Services\Broadcast\BroadcastExecutionService;
use App\Services\File\FileUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BroadcastController extends Controller
{
    public function __construct(
        private BroadcastExecutionService $executionService,
        private FileUploadService $fileUploadService
    ) {}

    public function index(Request $request)
    {
		$query = Broadcast::query();
		$mediaId = $request->user()?->media_id;

		if ($mediaId) {
			$query->where('media_id', $mediaId);
		} else {
			$query->whereRaw('1 = 0');
		}

		$broadcasts = $query->orderByDesc('broadcasted_at')
			->paginate($request->query('per_page', 15));

		return response()->json(['data' => $broadcasts]);
	}

	public function show(Request $request, Broadcast $broadcast): JsonResponse
	{
		if ($request->user()?->media_id && $broadcast->media_id !== $request->user()?->media_id) {
			abort(403, 'Cette diffusion ne vous concerne pas.');
		}

        return response()->json(['data' => [
            'id' => $broadcast->id,
            'title' => $broadcast->schedule?->campaign?->name ?? 'Diffusion',
            'status' => $broadcast->status,
            'broadcasted_at' => $broadcast->broadcasted_at?->toISOString(),
            'start_at' => $broadcast->schedule?->scheduled_at?->toISOString(),
            'proof_file_path' => $broadcast->proof_file_path ?? null,
            'payout' => $broadcast->schedule?->price ?? 0,
            'commission' => round(($broadcast->schedule?->price ?? 0) * 0.15, 4),
            'total_amount' => $broadcast->schedule?->price ?? 0,
            'advertiser' => $broadcast->schedule?->campaign?->advertiser?->name ?? null,
            'media' => $broadcast->media?->name,
            'campaign' => ['name' => $broadcast->schedule?->campaign?->name ?? null],
        ]]);
    }

    public function complete(Request $request, Broadcast $broadcast): JsonResponse
    {
        if ($request->user()?->media_id && $broadcast->media_id !== $request->user()?->media_id) {
            abort(403, 'Cette diffusion ne vous concerne pas.');
        }

        $status = $request->input('status', 'completed');
        if ($status === 'in_progress') {
            $this->executionService->startBroadcast($broadcast);
        } else {
            $this->executionService->completeBroadcast($broadcast, $broadcast->proof_file_path ?? null);
        }

        $broadcast->refresh();

        return response()->json(['message' => 'Statut mis à jour.', 'data' => ['status' => $broadcast->status]]);
    }

    public function uploadProof(Request $request, Broadcast $broadcast): JsonResponse
    {
        if ($request->user()?->media_id && $broadcast->media_id !== $request->user()?->media_id) {
            abort(403, 'Cette diffusion ne vous concerne pas.');
        }

        $request->validate([
            'file' => ['required'],
            'comment' => ['nullable', 'string'],
        ]);

        $path = null;
        if ($request->hasFile('file')) {
            $path = $this->fileUploadService->upload($request->file('file'), 'proofs');
        } else {
            $path = $request->input('file');
        }

        if ($path) {
            $broadcast->update(['proof_file_path' => $path]);
        }

        return response()->json(['message' => 'Preuve enregistrée.', 'proof_path' => $path]);
    }
}
