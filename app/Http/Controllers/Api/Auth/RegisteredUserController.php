<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Advertiser;
use App\Models\Media;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'email'            => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password'         => ['required', 'confirmed', Rules\Password::defaults()],
            'role'             => ['required', 'in:advertiser,media_manager'],
            'phone'            => ['nullable', 'string', 'max:30'],
            'company_name'     => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->string('password')),
            'role'     => $request->role,
        ]);

        if ($request->role === 'media_manager') {
            $media = Media::create([
                'name' => $request->company_name ?: $request->name,
                'type' => 'radio',
                'status' => 'active',
                'pricing_type' => 'fixed',
                'base_price' => 0,
                'description' => 'Média créé lors de l’inscription du media manager',
            ]);

            $user->forceFill(['media_id' => $media->id])->save();
        }

        // Créer le profil annonceur si c'est un client
        if ($request->role === 'advertiser') {
            Advertiser::create([
                'user_id'    => $user->id,
                'first_name' => $request->name,
                'last_name'  => '',
                'email'      => $request->email,
                'phone'      => $request->phone,
                'role'       => 'advertiser',
                'status'     => 'active',
            ]);
        }

        // Pour media_manager : tu pourras plus tard créer un profil Company / MediaOwner
        // if ($request->role === 'media_manager') { ... }

        event(new Registered($user));

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Utilisateur créé avec succès',
            'user'    => $user,
            'token'   => $token,
        ], 201);
    }
}