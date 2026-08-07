<?php

namespace App\Http\Controllers\Api\Media;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
	public function index(Request $request)
	{
		$query = Advertisement::query();
		$mediaId = $request->user()?->media_id;

		if ($mediaId) {
			$query->where('media_id', $mediaId);
		} else {
			$query->whereRaw('1 = 0');
		}

		$ads = $query->orderByDesc('created_at')
			->paginate($request->query('per_page', 15));

		return response()->json(['data' => $ads]);
	}

	public function show(Request $request, Advertisement $advertisement): JsonResponse
	{
		return response()->json(['data' => $advertisement]);
	}
}

