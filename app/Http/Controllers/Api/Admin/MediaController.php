<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Media\StoreMediaRequest;
use App\Http\Requests\Media\UpdateMediaRequest;
use App\Http\Ressources\MediaResource;
use App\Models\Media;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $media = Media::query()
            ->when($request->query('status'), fn ($query, $status) => $query->where('status', $status))
            ->when($request->query('type'), fn ($query, $type) => $query->where('type', $type))
            ->orderByDesc('created_at')
            ->paginate($request->query('per_page', 15));

        return response()->json(['data' => MediaResource::collection($media)]);
    }

    public function store(StoreMediaRequest $request): MediaResource
    {
        $media = Media::create($request->validated());

        return new MediaResource($media);
    }

    public function show(Media $media): MediaResource
    {
        return new MediaResource($media);
    }

    public function update(UpdateMediaRequest $request, Media $media): MediaResource
    {
        // Récupérer les données validées
        $data = $request->validated();

        // Mettre à jour
        $media->update($data);

        // Recharger le modèle pour avoir les valeurs fraîches
        $media->refresh();

        // Retourner la ressource (pas de JsonResponse)
        return new MediaResource($media);
    }

    public function destroy(Media $media): JsonResponse
    {
        $media->delete();

        return response()->json(['message' => 'Média administrateur supprimé avec succès']);
    }
}