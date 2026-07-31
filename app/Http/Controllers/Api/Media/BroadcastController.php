<?php

namespace App\Http\Controllers\Api\Media;

use App\Http\Controllers\Controller;
use App\Models\Broadcast;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BroadcastController extends Controller
{
	public function index(Request $request)
	{
		$query = Broadcast::query();

		if ($mediaId = $request->query('media_id')) {
			$query->where('media_id', $mediaId);
		}

		$broadcasts = $query->orderByDesc('broadcasted_at')
			->paginate($request->query('per_page', 15));

		return response()->json(['data' => $broadcasts]);
	}

	public function show(Broadcast $broadcast): JsonResponse
	{
		return response()->json(['data' => $broadcast]);
	}
}

