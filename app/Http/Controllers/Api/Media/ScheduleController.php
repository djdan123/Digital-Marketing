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
		$schedules = Schedule::when($request->query('media_id'), function ($q, $mediaId) {
				$q->where('media_id', $mediaId);
			})
			->orderBy('start_at')
			->paginate($request->query('per_page', 15));

		return response()->json(['data' => $schedules]);
	}

	public function show(Schedule $schedule): JsonResponse
	{
		return response()->json(['data' => $schedule]);
	}
}

