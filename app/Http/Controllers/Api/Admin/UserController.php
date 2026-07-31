<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Ressources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $users = User::query()
            ->when($request->query('email'), fn ($query, $email) => $query->where('email', 'like', "%{$email}%"))
            ->orderBy('name')
            ->paginate($request->query('per_page', 15));

        return response()->json(['data' => UserResource::collection($users)]);
    }

    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        ]);

        $user->update($data);

        return response()->json(['message' => 'Utilisateur mis à jour', 'data' => new UserResource($user)]);
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json(['message' => 'Utilisateur supprimé avec succès']);
    }
}