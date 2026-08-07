<?php

namespace App\Http\Controllers\Api\Media;

use App\Http\Controllers\Controller;
use App\Models\Broadcast;
use App\Models\Advertisement;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
	public function index(Request $request): JsonResponse
	{
		$mediaId = $request->user()?->media_id;
		$query = function ($builder) use ($mediaId) {
			if ($mediaId) {
				$builder->where('media_id', $mediaId);
			} else {
				$builder->whereRaw('1 = 0');
			}
		};

		$data = [
			'pending_ads' => Advertisement::where($query)->where('status', 'pending')->count(),
			'scheduled_broadcasts' => Broadcast::where($query)->where('broadcasted_at', '>=', now())->count(),
		];

		return response()->json(['data' => $data]);
	}
}

