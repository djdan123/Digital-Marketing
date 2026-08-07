<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Transaction::query()->where('type', 'commission');

        if ($request->filled('period')) {
            $period = $request->input('period');

            if ($period === 'month') {
                $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
            } elseif ($period === 'week') {
                $query->whereBetween('created_at', [now()->subWeek()->startOfDay(), now()->endOfDay()]);
            } elseif ($period === 'day') {
                $query->whereDate('created_at', now()->toDateString());
            }
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->input('end_date'));
        }

        $commissions = $query->orderByDesc('created_at')->get();

        return response()->json([
            'data' => $commissions->map(function (Transaction $transaction) {
                return [
                    'id' => $transaction->id,
                    'payment_id' => $transaction->payment_id,
                    'amount' => (float) $transaction->amount,
                    'currency' => $transaction->currency ?? 'FBU',
                    'created_at' => $transaction->created_at?->toIso8601String(),
                    'description' => data_get($transaction->details, 'description', 'Commission plateforme'),
                    'rate' => data_get($transaction->details, 'commission_rate', null),
                ];
            }),
            'meta' => [
                'total' => round((float) $commissions->sum('amount'), 4),
                'count' => $commissions->count(),
            ],
        ]);
    }
}
