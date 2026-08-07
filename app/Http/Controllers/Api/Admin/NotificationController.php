<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    /**
     * Liste des notifications (table notifications Laravel)
     */
    public function index(Request $request): JsonResponse
    {
        $query = DatabaseNotification::query()
            ->orderByDesc('created_at');

        if ($request->filled('type')) {
            $query->where('type', 'like', '%' . $request->type . '%');
        }

        $notifications = $query->paginate($request->get('per_page', 20));

        $items = collect($notifications->items())->map(function ($n) {
            $data = $n->data ?? [];
            return [
                'id'         => $n->id,
                'title'      => $data['title'] ?? $data['subject'] ?? class_basename($n->type),
                'message'    => $data['message'] ?? $data['body'] ?? null,
                'channel'    => $data['channel'] ?? 'database',
                'audience'   => $data['audience'] ?? null,
                'status'     => $n->read_at ? 'read' : 'sent',
                'created_at' => $n->created_at,
            ];
        });

        return response()->json([
            'data' => $items,
            'meta' => [
                'current_page' => $notifications->currentPage(),
                'last_page'    => $notifications->lastPage(),
                'per_page'     => $notifications->perPage(),
                'total'        => $notifications->total(),
            ],
        ]);
    }

    /**
     * Envoyer une notification aux utilisateurs selon l'audience
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title'    => ['required', 'string', 'max:255'],
            'message'  => ['required', 'string', 'max:5000'],
            'channel'  => ['required', 'in:email,sms,database,push'],
            'audience' => ['required', 'in:all,admin,advertiser,annonceur,media_manager,mediamanager'],
        ]);

        $usersQuery = User::query();

        switch ($data['audience']) {
            case 'admin':
                $usersQuery->where('role', 'admin');
                break;
            case 'advertiser':
            case 'annonceur':
                $usersQuery->whereIn('role', ['advertiser', 'annonceur']);
                break;
            case 'media_manager':
            case 'mediamanager':
                $usersQuery->whereIn('role', ['media_manager', 'mediamanager']);
                break;
            case 'all':
            default:
                // tous les utilisateurs
                break;
        }

        $users = $usersQuery->get();

        if ($users->isEmpty()) {
            return response()->json([
                'message' => 'Aucun destinataire trouvé pour cette audience.',
            ], 422);
        }

        $payload = [
            'title'    => $data['title'],
            'message'  => $data['message'],
            'channel'  => $data['channel'],
            'audience' => $data['audience'],
        ];

        // Notification database (toujours enregistrée)
        foreach ($users as $user) {
            $user->notify(new \App\Notifications\AdminBroadcastNotification($payload));
        }

        // Si canal email : envoi mail en plus (si la notification le gère)
        // Les canaux sms/push peuvent être branchés plus tard

        return response()->json([
            'message' => 'Notification envoyée à ' . $users->count() . ' destinataire(s)',
            'data'    => [
                'title'      => $data['title'],
                'channel'    => $data['channel'],
                'audience'   => $data['audience'],
                'recipients' => $users->count(),
                'status'     => 'sent',
            ],
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $notification = DatabaseNotification::findOrFail($id);
        $data = $notification->data ?? [];

        return response()->json([
            'data' => [
                'id'         => $notification->id,
                'title'      => $data['title'] ?? null,
                'message'    => $data['message'] ?? null,
                'channel'    => $data['channel'] ?? 'database',
                'audience'   => $data['audience'] ?? null,
                'status'     => $notification->read_at ? 'read' : 'sent',
                'created_at' => $notification->created_at,
            ],
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $notification = DatabaseNotification::findOrFail($id);
        $notification->delete();

        return response()->json([
            'message' => 'Notification supprimée',
        ]);
    }
}