<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $roles = Role::query()
            ->when($request->query('name'), fn ($query, $name) => $query->where('name', 'like', "%{$name}%"))
            ->orderBy('name')
            ->paginate($request->query('per_page', 15));

        return response()->json(['data' => $roles]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'guard_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $role = Role::create($data);

        return response()->json(['message' => 'Rôle créé avec succès', 'data' => $role], 201);
    }

    public function show(Role $role): JsonResponse
    {
        return response()->json(['data' => $role]);
    }

    public function update(Request $request, Role $role): JsonResponse
    {
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('roles', 'name')->ignore($role->id)],
            'guard_name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
        ]);

        $role->update($data);

        return response()->json(['message' => 'Rôle mis à jour', 'data' => $role]);
    }

    public function destroy(Role $role): JsonResponse
    {
        $role->delete();

        return response()->json(['message' => 'Rôle supprimé avec succès']);
    }
}