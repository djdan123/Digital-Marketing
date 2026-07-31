<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Ressources\CampaignCollection;
use App\Http\Ressources\CampaignResource;
use App\Http\Requests\Campaign\StoreCampaignRequest;
use App\Http\Requests\Campaign\UpdateCampaignRequest;
use App\Services\Contracts\CampaignServiceInterface;
use App\Models\Campaign;
use Illuminate\Http\JsonResponse;

class CampaignController extends Controller
{
    public function __construct(private CampaignServiceInterface $campaignService)
    {
    }

    public function index(): CampaignCollection
    {
        $campaigns = $this->campaignService->findByAdvertiser(auth()->id());

        return new CampaignCollection($campaigns);
    }

    public function store(StoreCampaignRequest $request): CampaignResource
    {
        $campaign = $this->campaignService->create($request->validatedDTO());

        return new CampaignResource($campaign);
    }

    public function show(Campaign $campaign): CampaignResource
    {
        return new CampaignResource($campaign);
    }

    public function update(UpdateCampaignRequest $request, Campaign $campaign): CampaignResource
    {
        $campaign = $this->campaignService->update($campaign, $request->validatedDTO());

        return new CampaignResource($campaign);
    }

    public function destroy(Campaign $campaign): JsonResponse
    {
        $campaign->delete();

        return response()->json(['message' => 'Campagne supprimée avec succès']);
    }
}
