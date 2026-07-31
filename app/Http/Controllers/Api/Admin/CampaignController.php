<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Campaign\StoreCampaignRequest;
use App\Http\Requests\Campaign\UpdateCampaignRequest;
use App\Http\Ressources\CampaignCollection;
use App\Http\Ressources\CampaignResource;
use App\Models\Campaign;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index(Request $request): CampaignCollection
    {
        $campaigns = Campaign::with(['advertiser', 'advertisements', 'schedules'])
            ->when($request->query('status'), fn ($query, $status) => $query->where('status', $status))
            ->when($request->query('advertiser_id'), fn ($query, $advertiserId) => $query->where('advertiser_id', $advertiserId))
            ->orderByDesc('created_at')
            ->paginate($request->query('per_page', 15));

        return new CampaignCollection($campaigns);
    }

    public function store(StoreCampaignRequest $request): CampaignResource
    {
        $campaign = Campaign::create($request->validated());

        return new CampaignResource($campaign);
    }

    public function show(Campaign $campaign): CampaignResource
    {
        return new CampaignResource($campaign->load(['advertiser', 'advertisements', 'schedules']));
    }

    public function update(UpdateCampaignRequest $request, Campaign $campaign): CampaignResource
    {
        $campaign->update($request->validated());

        return new CampaignResource($campaign);
    }

    public function destroy(Campaign $campaign): JsonResponse
    {
        $campaign->delete();

        return response()->json(['message' => 'Campagne administrateur supprimée avec succès']);
    }
}