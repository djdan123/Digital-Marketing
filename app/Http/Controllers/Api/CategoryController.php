<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $categories = Category::where('status', 'active')
            ->orderBy('name')
            ->paginate($request->query('per_page', 15));

        return response()->json(['data' => $categories]);
    }

    public function show(Category $category): JsonResponse
    {
        return response()->json(['data' => $category]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:categories,slug'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'string', Rule::in(['active', 'inactive'])],
        ]);

        $category = Category::create($data);

        return response()->json(['message' => 'Catégorie créée avec succès', 'data' => $category], 201);
    }

    public function update(Request $request, Category $category): JsonResponse
    {
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('categories', 'slug')->ignore($category->id)],
            'description' => ['sometimes', 'nullable', 'string'],
            'status' => ['sometimes', 'required', 'string', Rule::in(['active', 'inactive'])],
        ]);

        $category->update($data);

        return response()->json(['message' => 'Catégorie mise à jour', 'data' => $category]);
    }

    public function destroy(Category $category): JsonResponse
    {
        $category->delete();

        return response()->json(['message' => 'Catégorie supprimée avec succès']);
    }
}
