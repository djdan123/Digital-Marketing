<?php

namespace App\Services\Contracts;

use App\Models\Setting;

interface SettingServiceInterface extends ServiceInterface
{
    /**
     * Récupère un paramètre par sa clé.
     */
    public function get(string $key, $default = null): mixed;

    /**
     * Définit ou met à jour un paramètre.
     */
    public function set(string $key, $value, string $group = 'general'): Setting;

    /**
     * Récupère tous les paramètres d'un groupe.
     */
    public function getGroup(string $group): array;
}