<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Advertisement\ApproveAdvertisementRequest;
use App\Http\Ressources\AdvertisementResource;
use App\Models\Advertisement;
use App\Services\Contracts\AdvertisementServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    public function __construct(private AdvertisementServiceInterface $advertisementService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $advertisements = Advertisement::with(['campaign', 'media'])
            ->when($request->query('status'), fn ($query, $status) => $query->where('status', $status))
            ->orderByDesc('created_at')
            ->paginate($request->query('per_page', 15));

        return response()->json(['data' => AdvertisementResource::collection($advertisements)]);
    }

    public function show(Advertisement $advertisement): AdvertisementResource
    {
        return new AdvertisementResource($advertisement->load(['campaign', 'media']));
    }

    public function approve(ApproveAdvertisementRequest $request, Advertisement $advertisement): AdvertisementResource
    {
        $advertisement = $this->advertisementService->approve(
            $advertisement,
            $request->input('comments')
        );

        return new AdvertisementResource($advertisement);
    }

    public function reject(ApproveAdvertisementRequest $request, Advertisement $advertisement): AdvertisementResource
    {
        $advertisement = $this->advertisementService->reject(
            $advertisement,
            $request->input('comments')
        );

        return new AdvertisementResource($advertisement);
    }
}
