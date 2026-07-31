<?php

namespace App\Services\Setting;

use App\Models\Setting;
use App\Repositories\Contracts\SettingRepositoryInterface;

class SettingService
{
    public function __construct(
        private SettingRepositoryInterface $settingRepository
    ) {}

    /**
     * Récupère un paramètre par sa clé.
     */
    public function get(string $key, $default = null): mixed
    {
        $setting = $this->settingRepository->findByKey($key);
        return $setting ? $setting->value : $default;
    }

    /**
     * Définit ou met à jour un paramètre.
     */
    public function set(string $key, $value, string $group = 'general'): Setting
    {
        $setting = $this->settingRepository->findByKey($key);
        if ($setting) {
            return $this->settingRepository->update($setting, ['value' => $value]);
        }
        return $this->settingRepository->create([
            'key'   => $key,
            'value' => $value,
            'group' => $group,
        ]);
    }

    /**
     * Récupère tous les paramètres d'un groupe.
     */
    public function getGroup(string $group): array
    {
        $settings = $this->settingRepository->findByGroup($group);
        return $settings->pluck('value', 'key')->toArray();
    }
}