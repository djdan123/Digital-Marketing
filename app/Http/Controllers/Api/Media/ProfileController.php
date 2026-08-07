<?php

namespace App\Http\Controllers\Api\Media;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        if ($request->user()?->role !== 'media_manager') {
            abort(403, 'Accès réservé au media manager.');
        }

        $user = $request->user();
        $media = $user->media_id ? \App\Models\Media::find($user->media_id) : null;

        $accepted = Advertisement::where('media_id', $media?->id)
            ->whereIn('status', ['accepted', 'scheduled', 'completed'])
            ->sum('cost');

        $pendingPayouts = Advertisement::where('media_id', $media?->id)
            ->where('status', 'accepted')
            ->sum('cost');

        return response()->json(['data' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'media' => $media ? ['id' => $media->id, 'name' => $media->name, 'type' => $media->type] : null,
            'total_earnings' => (float) $accepted,
            'pending_payouts' => (float) $pendingPayouts,
        ]]);
    }
}
