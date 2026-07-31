<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $settings = Setting::query()
            ->when($request->query('group'), fn ($query, $group) => $query->where('group', $group))
            ->orderBy('key')
            ->paginate($request->query('per_page', 15));

        return response()->json(['data' => $settings]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'key' => ['required', 'string', 'max:255', 'unique:settings,key'],
            'group' => ['nullable', 'string', 'max:255'],
            'value' => ['nullable', 'string'],
            'details' => ['nullable', 'string'],
        ]);

        $setting = Setting::create($data);

        return response()->json(['message' => 'Paramètre créé avec succès', 'data' => $setting], 201);
    }

    public function show(Setting $setting): JsonResponse
    {
        return response()->json(['data' => $setting]);
    }

    public function update(Request $request, Setting $setting): JsonResponse
    {
        $data = $request->validate([
            'key' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('settings', 'key')->ignore($setting->id)],
            'group' => ['sometimes', 'nullable', 'string', 'max:255'],
            'value' => ['sometimes', 'nullable', 'string'],
            'details' => ['sometimes', 'nullable', 'string'],
        ]);

        $setting->update($data);

        return response()->json(['message' => 'Paramètre mis à jour', 'data' => $setting]);
    }

    public function destroy(Setting $setting): JsonResponse
    {
        $setting->delete();

        return response()->json(['message' => 'Paramètre supprimé avec succès']);
    }
}