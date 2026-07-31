<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Schedule\StoreScheduleRequest;
use App\Http\Ressources\ScheduleResource;
use App\Models\Schedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $schedules = Schedule::query()
            ->when($request->query('campaign_id'), fn ($query, $campaignId) => $query->where('campaign_id', $campaignId))
            ->when($request->query('advertisement_id'), fn ($query, $advertisementId) => $query->where('advertisement_id', $advertisementId))
            ->orderByDesc('scheduled_at')
            ->paginate($request->query('per_page', 15));

        return response()->json(['data' => ScheduleResource::collection($schedules)]);
    }

    public function store(StoreScheduleRequest $request): ScheduleResource
    {
        $schedule = Schedule::create($request->validated());

        return new ScheduleResource($schedule);
    }

    public function show(Schedule $schedule): ScheduleResource
    {
        return new ScheduleResource($schedule);
    }

    public function destroy(Schedule $schedule): JsonResponse
    {
        $schedule->delete();

        return response()->json(['message' => 'Planning supprimé avec succès']);
    }
}
