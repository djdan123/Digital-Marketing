<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Ressources\AdvertisementResource;
use App\Http\Requests\Advertisement\StoreAdvertisementRequest;
use App\Http\Requests\Advertisement\UpdateAdvertisementRequest;
use App\Services\Contracts\AdvertisementServiceInterface;
use App\Models\Advertisement;
use Illuminate\Http\JsonResponse;

class AdvertisementController extends Controller
{
    public function __construct(private AdvertisementServiceInterface $advertisementService)
    {
    }

    public function store(StoreAdvertisementRequest $request): AdvertisementResource
    {
        $advertisement = $this->advertisementService->create($request->validatedDTO());

        return new AdvertisementResource($advertisement);
    }

    public function show(Advertisement $advertisement): AdvertisementResource
    {
        return new AdvertisementResource($advertisement);
    }

    public function update(UpdateAdvertisementRequest $request, Advertisement $advertisement): AdvertisementResource
    {
        $advertisement = $this->advertisementService->update($advertisement, $request->validatedDTO());

        return new AdvertisementResource($advertisement);
    }

    public function destroy(Advertisement $advertisement): JsonResponse
    {
        $advertisement->delete();

        return response()->json(['message' => 'Annonce supprimée avec succès']);
    }
}
