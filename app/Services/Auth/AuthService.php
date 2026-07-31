<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    /**
     * Authentifie un utilisateur et retourne un token Sanctum.
     */
    public function login(array $credentials): array
    {
        $user = $this->userRepository->findByEmail($credentials['email']);

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Les identifiants sont incorrects.'],
            ]);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    /**
     * Révoque le token de l'utilisateur.
     */
    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }

    /**
     * Enregistre un nouvel utilisateur.
     */
    public function register(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        return $this->userRepository->create($data);
    }
}