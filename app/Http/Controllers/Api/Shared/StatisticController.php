<?php

namespace App\Http\Controllers\Api\Shared;

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
            ->when($request->query('date'), fn ($query, $date) => $query->where('date', $date))
            ->orderByDesc('date')
            ->paginate($request->query('per_page', 15));

        return response()->json(['data' => StatisticResource::collection($statistics)]);
    }

    public function show(Statistic $statistic): StatisticResource
    {
        return new StatisticResource($statistic);
    }
}