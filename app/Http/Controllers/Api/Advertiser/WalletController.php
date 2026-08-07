<?php

namespace App\Http\Controllers\Api\Advertiser;

use App\Http\Controllers\Controller;
use App\Models\Advertiser;
use App\Models\Report;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WalletController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $advertiser = $this->resolveAdvertiser($request);

        if (!$advertiser) {
            return response()->json(['message' => 'Profil annonceur introuvable'], 404);
        }

        return response()->json([
            'data' => [
                'balance'       => (float) ($advertiser->wallet_balance ?? 0),
                'currency'      => 'FBU',
                'advertiser_id' => $advertiser->id,
            ],
        ]);
    }

    public function requests(Request $request): JsonResponse
    {
        $advertiser = $this->resolveAdvertiser($request);

        if (!$advertiser) {
            return response()->json(['message' => 'Profil annonceur introuvable'], 404);
        }

        $items = Report::where('advertiser_id', $advertiser->id)
            ->where('type', 'wallet_request')
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json([
            'data' => $items->items(),
            'meta' => [
                'total' => $items->total(),
            ],
        ]);
    }

    public function storeRequest(Request $request): JsonResponse
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:100'],
            'note'   => ['nullable', 'string', 'max:500'],
        ]);

        $advertiser = $this->resolveAdvertiser($request);

        if (!$advertiser) {
            return response()->json([
                'message' => 'Profil annonceur introuvable. Impossible de créer la demande.',
            ], 404);
        }

        try {
            $report = Report::create([
                'advertiser_id' => $advertiser->id,
                'campaign_id'   => null,
                'title'         => 'Demande de recharge portefeuille',
                'description'   => $data['note'] ?? 'Demande de recharge',
                'type'          => 'wallet_request',
                'filters'       => [
                    'amount'   => (float) $data['amount'],
                    'currency' => 'FBU',
                ],
                'results'       => [
                    'status' => 'pending',
                ],
            ]);

            Log::info('Wallet request created', [
                'report_id'     => $report->id,
                'advertiser_id' => $advertiser->id,
                'amount'        => $data['amount'],
            ]);

            return response()->json([
                'message' => 'Demande envoyée avec succès. Elle sera examinée prochainement.',
                'data'    => $report,
            ], 201);

        } catch (\Throwable $e) {
            Log::error('Wallet request error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return response()->json([
                'message' => 'Erreur lors de la création de la demande: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function resolveAdvertiser(Request $request): ?Advertiser
    {
        $user = $request->user();

        if (!$user) {
            return null;
        }

        return Advertiser::where('user_id', $user->id)->first()
            ?? Advertiser::where('email', $user->email)->first();
    }
}