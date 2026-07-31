<?php

namespace App\Http\Requests\Media;

use App\Enums\MediaType;
use App\Enums\PricingType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'company_id' => ['required', 'integer', 'exists:companies,id'],
            'type' => ['required', 'string', Rule::in(array_map(fn($value) => $value->value, MediaType::cases()))],
            'pricing_type' => ['required', 'string', Rule::in(array_map(fn($value) => $value->value, PricingType::cases()))],
            'base_price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', 'string', Rule::in(['active', 'inactive'])],
        ];
    }
}
