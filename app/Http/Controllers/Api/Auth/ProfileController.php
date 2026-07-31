<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
	public function show(Request $request): JsonResponse
	{
		return response()->json(['data' => $request->user()]);
	}

	public function update(Request $request): JsonResponse
	{
		$user = $request->user();

		$data = $request->validate([
			'name' => ['sometimes','required','string','max:255'],
			'email' => ['sometimes','required','email','max:255', Rule::unique('users','email')->ignore($user->id)],
		]);

		$user->update($data);

		return response()->json(['message' => 'Profil mis à jour', 'data' => $user]);
	}
}

