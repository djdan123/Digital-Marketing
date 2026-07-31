<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
	public function update(Request $request): JsonResponse
	{
		$request->validate([
			'current_password' => ['required','string'],
			'password' => ['required','string','min:8','confirmed'],
		]);

		$user = $request->user();

		if (! Hash::check($request->input('current_password'), $user->password)) {
			return response()->json(['message' => 'Mot de passe actuel invalide'], 422);
		}

		$user->password = Hash::make($request->input('password'));
		$user->save();

		return response()->json(['message' => 'Mot de passe mis à jour']);
	}
}

