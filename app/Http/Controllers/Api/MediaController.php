<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Media\StoreMediaRequest;
use App\Http\Requests\Media\UpdateMediaRequest;
use App\Http\Ressources\MediaResource;
use App\Models\Media;
use App\Services\Contracts\MediaServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function __construct(private MediaServiceInterface $mediaService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 15);

        if ($type = $request->query('type')) {
            $media = $this->mediaService->allActiveByType($type, $perPage);
        } else {
            $media = Media::where('status', 'active')->paginate($perPage);
        }

        return response()->json(['data' => MediaResource::collection($media)]);
    }

    public function show(Media $media): MediaResource
    {
        return new MediaResource($media);
    }

    public function store(StoreMediaRequest $request): MediaResource
    {
        $media = Media::create($request->validated());

        return new MediaResource($media);
    }

    public function update(UpdateMediaRequest $request, Media $media): MediaResource
    {
        $media->update($request->validated());

        return new MediaResource($media);
    }

    public function destroy(Media $media): JsonResponse
    {
        $media->delete();

        return response()->json(['message' => 'Média supprimé avec succès']);
    }
}
