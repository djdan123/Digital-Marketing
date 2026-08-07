<?php

namespace App\Http\Controllers\Api\Advertiser;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $reports = Report::where('advertiser_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate($request->query('per_page', 15));

        return response()->json(['data' => $reports]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'campaign_id' => ['sometimes', 'integer', 'exists:campaigns,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'string', Rule::in(['campaign', 'media', 'payment', 'performance', 'wallet_request'])],
            'filters' => ['nullable', 'array'],
            'results' => ['nullable', 'array'],
        ]);

        $report = Report::create(array_merge($data, ['advertiser_id' => auth()->id()]));

        return response()->json(['message' => 'Rapport créé avec succès', 'data' => $report], 201);
    }

    public function show(Report $report): JsonResponse
    {
        abort_unless($report->advertiser_id === auth()->id(), 403);

        return response()->json(['data' => $report]);
    }

    public function update(Request $request, Report $report): JsonResponse
    {
        abort_unless($report->advertiser_id === auth()->id(), 403);

        $data = $request->validate([
            'campaign_id' => ['sometimes', 'integer', 'exists:campaigns,id'],
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'type' => ['sometimes', 'required', 'string', Rule::in(['campaign', 'media', 'payment', 'performance', 'wallet_request'])],
            'filters' => ['sometimes', 'nullable', 'array'],
            'results' => ['sometimes', 'nullable', 'array'],
        ]);

        $report->update($data);

        return response()->json(['message' => 'Rapport mis à jour', 'data' => $report]);
    }

    public function destroy(Report $report): JsonResponse
    {
        abort_unless($report->advertiser_id === auth()->id(), 403);

        $report->delete();

        return response()->json(['message' => 'Rapport supprimé avec succès']);
    }
}