<?php

namespace App\Http\Controllers\Api\Advertiser;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Payment;
use App\Models\Report;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        $advertiserId = auth()->id();

        $campaigns = Campaign::where('advertiser_id', $advertiserId)->count();
        $totalPayments = Payment::where('advertiser_id', $advertiserId)->sum('amount');
        $reports = Report::where('advertiser_id', $advertiserId)->count();
        $activeCampaigns = Campaign::where('advertiser_id', $advertiserId)->where('status', 'active')->count();

        return response()->json(['data' => compact('campaigns', 'totalPayments', 'reports', 'activeCampaigns')]);
    }
}