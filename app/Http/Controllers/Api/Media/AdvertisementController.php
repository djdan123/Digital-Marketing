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
		$ads = Advertisement::where('media_id', $request->query('media_id'))
			->orderByDesc('created_at')
			->paginate($request->query('per_page', 15));

		return response()->json(['data' => $ads]);
	}

	public function show(Advertisement $advertisement): JsonResponse
	{
		return response()->json(['data' => $advertisement]);
	}
}

