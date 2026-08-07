<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    /**
     * Clés de tarification standard
     */
    private array $keys = [
        'price_image',
        'price_text',
        'price_audio',
        'price_video',
        'commission_rate',
    ];

    /**
     * Valeurs par défaut (FBU)
     */
    private array $defaults = [
        'price_image'      => '500',
        'price_text'       => '2000',
        'price_audio'      => '10000',
        'price_video'      => '50000',
        'commission_rate'  => '15',
    ];

    public function index(): JsonResponse
    {
        $settings = Setting::where('group', 'pricing')
            ->whereIn('key', $this->keys)
            ->get()
            ->keyBy('key');

        $data = [];
        foreach ($this->keys as $key) {
            $data[$key] = $settings->get($key)?->value ?? $this->defaults[$key];
        }

        return response()->json([
            'data' => $data,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        return $this->save($request);
    }

    public function update(Request $request): JsonResponse
    {
        return $this->save($request);
    }

    private function save(Request $request): JsonResponse
    {
        $data = $request->validate([
            'price_image'     => ['sometimes', 'numeric', 'min:0'],
            'price_text'      => ['sometimes', 'numeric', 'min:0'],
            'price_audio'     => ['sometimes', 'numeric', 'min:0'],
            'price_video'     => ['sometimes', 'numeric', 'min:0'],
            'commission_rate' => ['sometimes', 'numeric', 'min:0', 'max:100'],
        ]);

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key, 'group' => 'pricing'],
                [
                    'value'   => (string) $value,
                    'details' => $this->labelFor($key),
                ]
            );
        }

        // Recharger les valeurs finales
        $settings = Setting::where('group', 'pricing')
            ->whereIn('key', $this->keys)
            ->get()
            ->keyBy('key');

        $result = [];
        foreach ($this->keys as $key) {
            $result[$key] = $settings->get($key)?->value ?? $this->defaults[$key];
        }

        return response()->json([
            'message' => 'Tarifs enregistrés avec succès',
            'data'    => $result,
        ]);
    }

    private function labelFor(string $key): string
    {
        return match ($key) {
            'price_image'     => 'Prix image (FBU / diffusion)',
            'price_text'      => 'Prix texte (FBU / diffusion)',
            'price_audio'     => 'Prix audio (FBU / minute)',
            'price_video'     => 'Prix vidéo (FBU / minute)',
            'commission_rate' => 'Commission plateforme (%)',
            default           => $key,
        };
    }
}