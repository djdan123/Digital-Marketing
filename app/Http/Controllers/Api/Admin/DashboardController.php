<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\Campaign;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $commissionsQuery = Transaction::query()->where('type', 'commission');
        $period = $request->input('period', 'all');

        if ($period === 'month') {
            $commissionsQuery->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
        } elseif ($period === 'week') {
            $commissionsQuery->whereBetween('created_at', [now()->subWeek()->startOfDay(), now()->endOfDay()]);
        } elseif ($period === 'day') {
            $commissionsQuery->whereDate('created_at', now()->toDateString());
        }

        $data = [
            'campaigns_count' => Campaign::count(),
            'advertisements_count' => Advertisement::count(),
            'users_count' => User::count(),
            'payments_total' => Payment::sum('amount'),
            'commissions_total' => round((float) Transaction::query()->where('type', 'commission')->sum('amount'), 4),
            'commissions_period' => round((float) $commissionsQuery->sum('amount'), 4),
            'commission_rate' => (float) config('truckall.commission_rate', 0.15),
        ];

        return response()->json(['data' => $data]);
    }
}

