<?php

namespace App\Services\Contracts;

use App\Models\User;

interface AuthServiceInterface extends ServiceInterface
{
    /**
     * Authentifie un utilisateur et retourne un token.
     */
    public function login(array $credentials): array;

    /**
     * Révoque le token de l'utilisateur.
     */
    public function logout(User $user): void;

    /**
     * Enregistre un nouvel utilisateur.
     */
    public function register(array $data): User;
}