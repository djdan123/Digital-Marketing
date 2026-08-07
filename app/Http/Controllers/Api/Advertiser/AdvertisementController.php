<?php

namespace App\Http\Controllers\Api\Advertiser;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
	public function index(Request $request)
	{
		$ads = Advertisement::whereHas('campaign', fn($q) => $q->where('advertiser_id', auth()->id()))
			->orderByDesc('created_at')
			->paginate($request->query('per_page', 15));

		return response()->json(['data' => $ads]);
	}

	public function store(Request $request): JsonResponse
	{
		$data = $request->validate([
			'campaign_id' => ['required','integer'],
			'media_id' => ['required','integer'],
			'title' => ['required','string','max:255'],
			'description' => ['nullable','string'],
			'format' => ['nullable','string'],
			'meta' => ['nullable','array'],
			'meta.file_path' => ['nullable','string'],
			'meta.file_url' => ['nullable','string'],
			'meta.filename' => ['nullable','string'],
			'meta.mime' => ['nullable','string'],
			'meta.type' => ['nullable','string'],
			'meta.size' => ['nullable','integer'],
		]);

		$ad = Advertisement::create([
			'campaign_id' => $data['campaign_id'],
			'media_id' => $data['media_id'],
			'title' => $data['title'],
			'description' => $data['description'] ?? null,
			'format' => $data['format'] ?? null,
			'meta' => $data['meta'] ?? null,
		]);

		return response()->json(['message' => 'Annonce créée', 'data' => $ad], 201);
	}

	public function show(Advertisement $advertisement): JsonResponse
	{
		abort_unless($advertisement->campaign && $advertisement->campaign->advertiser_id === auth()->id(), 403);

		return response()->json(['data' => $advertisement]);
	}

	public function update(Request $request, Advertisement $advertisement): JsonResponse
	{
		abort_unless($advertisement->campaign && $advertisement->campaign->advertiser_id === auth()->id(), 403);

		$data = $request->validate([
			'title' => ['sometimes','required','string','max:255'],
			'description' => ['sometimes','nullable','string'],
		]);

		$advertisement->update($data);

		return response()->json(['message' => 'Annonce mise à jour', 'data' => $advertisement]);
	}

	public function destroy(Advertisement $advertisement): JsonResponse
	{
		abort_unless($advertisement->campaign && $advertisement->campaign->advertiser_id === auth()->id(), 403);

		$advertisement->delete();

		return response()->json(['message' => 'Annonce supprimée']);
	}
}

