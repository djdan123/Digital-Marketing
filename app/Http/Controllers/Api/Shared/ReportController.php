<?php

namespace App\Http\Controllers\Api\Shared;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $reports = Report::query()
            ->when($request->query('type'), fn ($query, $type) => $query->where('type', $type))
            ->orderByDesc('created_at')
            ->paginate($request->query('per_page', 15));

        return response()->json(['data' => $reports]);
    }

    public function show(Report $report): JsonResponse
    {
        return response()->json(['data' => $report]);
    }
}