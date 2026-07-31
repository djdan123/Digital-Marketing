<?php

namespace App\Http\Controllers\Api\Media;

use App\Http\Controllers\Controller;
use App\Models\Broadcast;
use App\Models\Advertisement;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
	public function index(): JsonResponse
	{
		$data = [
			'pending_ads' => Advertisement::where('status', 'pending')->count(),
			'scheduled_broadcasts' => Broadcast::where('broadcasted_at', '>=', now())->count(),
		];

		return response()->json(['data' => $data]);
	}
}

