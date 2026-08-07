<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertiser;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdvertiserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $advertisers = Advertiser::query()
            ->with('company')
            ->when($request->filled('status'), fn ($query, $status) => $query->where('status', $status))
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $advertiserUsers = User::query()
            ->whereNotNull('email')
            ->where(function ($query): void {
                $query->where('role', 'annonceur')
                    ->orWhere('role', 'advertiser')
                    ->orWhere('role', 'Annonceur')
                    ->orWhereRaw('LOWER(role) = ?', ['advertiser']);
            })
            ->get();

        $linkedUserIds = $advertisers->pluck('user_id')->filter()->map(fn ($id) => (int) $id)->all();

        foreach ($advertiserUsers as $user) {
            if (in_array((int) $user->id, $linkedUserIds, true)) {
                continue;
            }

            $advertisers->push(
                Advertiser::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'company_id' => null,
                        'first_name' => $user->name,
                        'last_name' => '',
                        'phone' => null,
                        'email' => $user->email,
                        'role' => 'advertiser',
                        'status' => 'active',
                    ]
                )
            );
            $linkedUserIds[] = (int) $user->id;
        }

        $payload = $advertisers->map(function (Advertiser $advertiser): array {
            return [
                'id' => $advertiser->id,
                'name' => $advertiser->full_name ?: 'Annonceur #' . $advertiser->id,
                'email' => $advertiser->email,
                'status' => $advertiser->status,
                'company' => $advertiser->company?->name ?? null,
            ];
        })->values();

        return response()->json([
            'data' => $payload,
            'meta' => [
                'total' => $payload->count(),
            ],
        ]);
    }

    public function show(Advertiser $advertiser): JsonResponse
    {
        return response()->json([
            'data' => [
                'id' => $advertiser->id,
                'name' => $advertiser->full_name ?: 'Annonceur #' . $advertiser->id,
                'email' => $advertiser->email,
                'status' => $advertiser->status,
                'company' => $advertiser->company?->name ?? null,
            ],
        ]);
    }
}
