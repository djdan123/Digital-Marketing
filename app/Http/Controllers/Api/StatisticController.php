<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Ressources\StatisticResource;
use App\Models\Statistic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $statistics = Statistic::query()
            ->when($request->query('entity_type'), fn ($query, $entityType) => $query->where('statisticable_type', $entityType))
            ->when($request->query('entity_id'), fn ($query, $entityId) => $query->where('statisticable_id', $entityId))
            ->orderByDesc('date')
            ->paginate($request->query('per_page', 15));

        return response()->json(['data' => StatisticResource::collection($statistics)]);
    }

    public function show(Statistic $statistic): StatisticResource
    {
        return new StatisticResource($statistic);
    }
}
