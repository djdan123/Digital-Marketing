<?php

namespace App\Http\Controllers\Api\Media;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\Broadcast;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        if ($request->user()?->role !== 'media_manager') {
            abort(403, 'Accès réservé au media manager.');
        }

        $mediaId = $request->user()?->media_id;

        $requests = Advertisement::where('media_id', $mediaId)->get();
        $broadcasts = Broadcast::where('media_id', $mediaId)->get();

        $accepted = $requests->whereIn('status', ['accepted', 'scheduled', 'completed'])->count();
        $acceptanceRate = $requests->count() > 0 ? round(($accepted / $requests->count()) * 100, 1) : 0;

        return response()->json(['data' => [
            'requests_count' => $requests->count(),
            'acceptance_rate' => $acceptanceRate,
            'broadcasts_count' => $broadcasts->count(),
            'series' => [
                ['label' => 'S1', 'value' => $requests->whereIn('status', ['accepted', 'completed'])->count()],
                ['label' => 'S2', 'value' => $broadcasts->where('status', 'scheduled')->count()],
                ['label' => 'S3', 'value' => $broadcasts->where('status', 'completed')->count()],
                ['label' => 'S4', 'value' => $broadcasts->count()],
            ],
        ]]);
    }
}
