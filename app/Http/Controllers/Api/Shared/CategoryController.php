<?php

namespace App\Http\Controllers\Api\Shared;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $categories = Category::query()
            ->when($request->query('search'), fn ($query, $search) => $query->where('name', 'like', "%{$search}%"))
            ->orderBy('name')
            ->paginate($request->query('per_page', 15));

        return response()->json(['data' => $categories]);
    }

    public function show(Category $category): JsonResponse
    {
        return response()->json(['data' => $category]);
    }
}