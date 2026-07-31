<?php

namespace App\Http\Controllers\Api\Shared;

use App\Http\Controllers\Controller;
use App\Http\Ressources\MediaResource;
use App\Models\Media;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $media = Media::query()
            ->when($request->query('type'), fn ($query, $type) => $query->where('type', $type))
            ->when($request->query('status'), fn ($query, $status) => $query->where('status', $status))
            ->orderByDesc('created_at')
            ->paginate($request->query('per_page', 15));

        return response()->json(['data' => MediaResource::collection($media)]);
    }

    public function show(Media $media): MediaResource
    {
        return new MediaResource($media);
    }
}