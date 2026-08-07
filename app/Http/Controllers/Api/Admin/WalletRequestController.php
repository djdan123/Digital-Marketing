<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertiser;
use App\Models\Report;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalletRequestController extends Controller
{
    /**
     * Liste toutes les demandes de recharge
     */
    public function index(Request $request): JsonResponse
    {
        $query = Report::with(['advertiser.user'])
            ->where('type', 'wallet_request')
            ->orderByDesc('created_at');

        // Filtrer par statut si fourni
        if ($status = $request->get('status')) {
            $query->where('results->status', $status);
        }

        $items = $query->paginate($request->get('per_page', 20));

        // Normaliser pour le front admin
        $data = collect($items->items())->map(function (Report $r) {
            return [
                'id'            => $r->id,
                'advertiser_id' => $r->advertiser_id,
                'advertiser'    => [
                    'id'    => $r->advertiser?->id,
                    'name'  => $r->advertiser?->full_name
                        ?? $r->advertiser?->user?->name
                        ?? $r->advertiser?->email
                        ?? '—',
                    'email' => $r->advertiser?->email,
                ],
                'user'          => $r->advertiser?->user,
                'amount'        => (float) data_get($r->filters, 'amount', 0),
                'currency'      => data_get($r->filters, 'currency', 'FBU'),
                'note'          => $r->description,
                'description'   => $r->description,
                'status'        => data_get($r->results, 'status', 'pending'),
                'filters'       => $r->filters,
                'results'       => $r->results,
                'created_at'    => $r->created_at,
            ];
        });

        return response()->json([
            'data' => $data,
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'total'        => $items->total(),
            ],
        ]);
    }

    /**
     * Approuver → créditer le portefeuille
     */
    public function approve(Request $request, int $report_id): JsonResponse
    {
        $report = Report::where('type', 'wallet_request')->findOrFail($report_id);

        $status = data_get($report->results, 'status', 'pending');
        if ($status !== 'pending') {
            return response()->json([
                'message' => 'Cette demande a déjà été traitée.',
            ], 422);
        }

        $amount = (float) data_get($report->filters, 'amount', 0);
        if ($amount <= 0) {
            return response()->json(['message' => 'Montant invalide'], 422);
        }

        DB::transaction(function () use ($report, $amount) {
            $advertiser = Advertiser::findOrFail($report->advertiser_id);

            // Créditer le solde (adapte le nom de colonne si besoin)
            if (\Schema::hasColumn('advertisers', 'wallet_balance')) {
                $advertiser->wallet_balance = (float) ($advertiser->wallet_balance ?? 0) + $amount;
                $advertiser->save();
            } elseif (\Schema::hasColumn('advertisers', 'balance')) {
                $advertiser->balance = (float) ($advertiser->balance ?? 0) + $amount;
                $advertiser->save();
            }

            $report->results = array_merge($report->results ?? [], [
                'status'      => 'approved',
                'approved_at' => now()->toISOString(),
                'amount'      => $amount,
            ]);
            $report->save();
        });

        return response()->json([
            'message' => 'Demande approuvée. Portefeuille crédité.',
            'data'    => $report->fresh(),
        ]);
    }

    /**
     * Refuser la demande
     */
    public function reject(Request $request, int $report_id): JsonResponse
    {
        $report = Report::where('type', 'wallet_request')->findOrFail($report_id);

        $status = data_get($report->results, 'status', 'pending');
        if ($status !== 'pending') {
            return response()->json([
                'message' => 'Cette demande a déjà été traitée.',
            ], 422);
        }

        $report->results = array_merge($report->results ?? [], [
            'status'     => 'rejected',
            'rejected_at'=> now()->toISOString(),
            'reason'     => $request->input('reason'),
        ]);
        $report->save();

        return response()->json([
            'message' => 'Demande refusée.',
            'data'    => $report,
        ]);
    }
}