<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MediaType;
use App\Enums\PricingType;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Company;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Services\Contracts\MediaServiceInterface;

class MediaController extends Controller
{
    public function __construct(private MediaServiceInterface $mediaService)
    {
        $this->authorizeResource(Media::class, 'media');
    }

    public function index()
    {
        return view('admin.media', [
            'medias' => Media::with('company')->paginate(15),
        ]);
    }

    public function create()
    {
        return view('admin.create-media', [
            'categories' => Category::all(),
            'companies' => Company::active()->get(),
            'types' => MediaType::cases(),
            'pricingTypes' => PricingType::cases(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'company_id' => ['required', 'integer', 'exists:companies,id'],
            'type' => ['required', 'string', Rule::in(array_map(fn($value) => $value->value, MediaType::cases()))],
            'pricing_type' => ['required', 'string', Rule::in(array_map(fn($value) => $value->value, PricingType::cases()))],
            'base_price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', 'string', Rule::in(['active', 'inactive'])],
        ]);

        Media::create($data);

        return redirect()->route('admin.web.media.index')->with('success', 'Média créé.');
    }

    public function edit(Media $media)
    {
        return view('admin.edit-media', [
            'media' => $media,
            'categories' => Category::all(),
            'companies' => Company::active()->get(),
            'types' => MediaType::cases(),
            'pricingTypes' => PricingType::cases(),
        ]);
    }

    public function update(Request $request, Media $media)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'company_id' => ['required', 'integer', 'exists:companies,id'],
            'type' => ['required', 'string', Rule::in(array_map(fn($value) => $value->value, MediaType::cases()))],
            'pricing_type' => ['required', 'string', Rule::in(array_map(fn($value) => $value->value, PricingType::cases()))],
            'base_price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', 'string', Rule::in(['active', 'inactive'])],
        ]);

        $media->update($data);

        return redirect()->route('admin.web.media.index')->with('success', 'Média mis à jour.');
    }

    public function destroy(Media $media)
    {
        $media->delete();

        return redirect()->route('admin.web.media.index')->with('success', 'Média supprimé.');
    }
}
