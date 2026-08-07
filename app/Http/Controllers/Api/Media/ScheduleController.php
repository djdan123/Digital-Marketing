<?php

namespace App\Http\Controllers\Api\Media;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
		$query = Schedule::query();
		$mediaId = $request->user()?->media_id;

		if ($mediaId) {
			$query->where('media_id', $mediaId);
		} else {
			$query->whereRaw('1 = 0');
		}

		$schedules = $query->orderBy('start_at')
			->paginate($request->query('per_page', 15));

		return response()->json(['data' => $schedules]);
	}

    public function show(Request $request, Schedule $schedule): JsonResponse
    {
        if ($request->user()?->media_id && $schedule->media_id !== $request->user()?->media_id) {
            abort(403, 'Ce planning ne vous concerne pas.');
        }

        return response()->json(['data' => [
            'id' => $schedule->id,
            'title' => $schedule->campaign?->name ?? 'Créneau',
            'media_id' => $schedule->media_id,
            'advertiser_id' => $schedule->campaign?->advertiser_id,
            'start_at' => $schedule->scheduled_at?->toISOString(),
            'end_at' => $schedule->meta['end_at'] ?? null,
            'status' => $schedule->status,
            'payout' => (float) $schedule->price,
            'commission' => round((float) $schedule->price * 0.15, 4),
            'total_amount' => (float) $schedule->price,
        ]]);
    }

    public function update(Request $request, Schedule $schedule): JsonResponse
    {
        if ($request->user()?->media_id && $schedule->media_id !== $request->user()?->media_id) {
            abort(403, 'Ce planning ne vous concerne pas.');
        }

        $data = $request->validate([
            'start_at' => ['required', 'date'],
            'end_at' => ['required', 'date'],
        ]);

        $schedule->update(['scheduled_at' => $data['start_at']]);

        if (is_array($schedule->meta ?? null)) {
            $meta = $schedule->meta;
        } else {
            $meta = [];
        }
        $meta['end_at'] = $data['end_at'];
        $schedule->meta = $meta;
        $schedule->save();

        return response()->json(['message' => 'Planning mis à jour.']);
    }
}

