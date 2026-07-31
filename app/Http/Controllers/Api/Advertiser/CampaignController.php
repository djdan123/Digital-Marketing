<?php

namespace App\Http\Controllers\Api\Advertiser;

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
        $campaigns = Campaign::where('advertiser_id', auth()->id())
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
        abort_unless($campaign->advertiser_id === auth()->id(), 403);

        return new CampaignResource($campaign);
    }

    public function update(UpdateCampaignRequest $request, Campaign $campaign): CampaignResource
    {
        abort_unless($campaign->advertiser_id === auth()->id(), 403);

        $campaign->update($request->validated());

        return new CampaignResource($campaign);
    }

    public function destroy(Campaign $campaign): JsonResponse
    {
        abort_unless($campaign->advertiser_id === auth()->id(), 403);

        $campaign->delete();

        return response()->json(['message' => 'Campagne supprimée avec succès']);
    }
}