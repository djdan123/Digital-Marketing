<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function login(Request $request): JsonResponse
            {
                $request->validate([
                    'email' => ['required', 'email'],
                    'password' => ['required', 'string'],
                    'device_name' => ['sometimes', 'string'], // optionnel
                ]);

                if (! Auth::attempt($request->only('email', 'password'))) {
                    throw ValidationException::withMessages([
                        'email' => ['Les identifiants fournis sont incorrects.'],
                    ]);
                }

                $user = Auth::user();
                $token = $user->createToken($request->device_name ?? 'auth-token')->plainTextToken;

                return response()->json([
                    'user'  => $user,
                    'token' => $token,
                ]);
            }
    public function store(LoginRequest $request): Response
    {
        $request->authenticate();

        $request->session()->regenerate();

        return response()->noContent();
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
