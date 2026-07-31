<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
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

