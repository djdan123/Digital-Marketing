<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Ressources\UserResource;
use App\Models\User;
use App\Services\Contracts\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct(private UserServiceInterface $userService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 15);

        if ($role = $request->query('role')) {
            $users = $this->userService->findByRole($role, $perPage);
        } else {
            $users = User::paginate($perPage);
        }

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
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['sometimes', 'required', 'string'],
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
