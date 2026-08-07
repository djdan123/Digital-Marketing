<?php

namespace App\Http\Controllers\Api\Advertiser;

use App\Http\Controllers\Controller;
use App\Http\Requests\Campaign\StoreCampaignRequest;
use App\Http\Requests\Campaign\UpdateCampaignRequest;
use App\Http\Ressources\CampaignCollection;
use App\Http\Ressources\CampaignResource;
use App\Models\Advertiser;
use App\Models\Campaign;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index(Request $request): CampaignCollection
    {
        $advertiserId = $this->resolveAdvertiserId($request);

        $campaigns = Campaign::where('advertiser_id', $advertiserId)
            ->orderByDesc('created_at')
            ->paginate($request->query('per_page', 15));

        return new CampaignCollection($campaigns);
    }

    public function store(StoreCampaignRequest $request): CampaignResource
    {
        $data = $request->validated();
        $data['advertiser_id'] = $data['advertiser_id'] ?? $this->resolveAdvertiserId($request);

        $campaign = Campaign::create($data);

        return new CampaignResource($campaign);
    }

    public function show(Campaign $campaign): CampaignResource
    {
        abort_unless($campaign->advertiser_id === $this->resolveAdvertiserId(request()), 403);

        $campaign->load(['advertisements.media']);

        return new CampaignResource($campaign);
    }

    public function update(UpdateCampaignRequest $request, Campaign $campaign): CampaignResource
    {
        abort_unless($campaign->advertiser_id === $this->resolveAdvertiserId($request), 403);

        $campaign->update($request->validated());

        return new CampaignResource($campaign);
    }

    public function destroy(Request $request, Campaign $campaign): JsonResponse
    {
        abort_unless($campaign->advertiser_id === $this->resolveAdvertiserId($request), 403);

        $campaign->delete();

        return response()->json(['message' => 'Campagne supprimée avec succès']);
    }

    private function resolveAdvertiserId(Request $request): int
    {
        $user = $request->user();

        if (!$user) {
            abort(401, 'Authentification requise.');
        }

        $advertiser = Advertiser::where('user_id', $user->id)->first()
            ?? Advertiser::where('email', $user->email)->first();

        if (!$advertiser) {
            abort(422, 'Profil annonceur introuvable.');
        }

        return $advertiser->id;
    }
}