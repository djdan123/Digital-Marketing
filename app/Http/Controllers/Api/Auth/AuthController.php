<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{	
		public function login(Request $request): JsonResponse
	{
		$request->validate([
			'email' => ['required', 'email'],
			'password' => ['required', 'string'],
			'device_name' => ['sometimes', 'string'], // optionnel
		]);

		$credentials = $request->only('email', 'password');
		$credentials['email'] = trim(strtolower($credentials['email']));

		if (! Auth::attempt($credentials)) {
			return response()->json([
				'message' => 'Les identifiants fournis sont incorrects.',
			], 401);
		}

		$user = Auth::user();
		$token = $user->createToken($request->device_name ?? 'auth-token')->plainTextToken;

		return response()->json([
			'user'  => $user,
			'token' => $token,
		]);
	}
	public function me(Request $request): JsonResponse
	{
		return response()->json(['data' => $request->user()]);
	}

	public function logout(Request $request): JsonResponse
	{
		$request->user()?->currentAccessToken()?->delete();

		Auth::logout();

		return response()->json(['message' => 'Déconnecté']);
	}
}

