<?php

namespace App\Http\Requests\Media;

use App\Enums\MediaType;
use App\Enums\PricingType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'category_id' => ['sometimes', 'integer', 'exists:categories,id'],
            'company_id' => ['sometimes', 'integer', 'exists:companies,id'],
            'type' => ['sometimes', 'string', Rule::in(array_map(fn($value) => $value->value, MediaType::cases()))],
            'pricing_type' => ['sometimes', 'string', Rule::in(array_map(fn($value) => $value->value, PricingType::cases()))],
            'base_price' => ['sometimes', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:2000'],
            'status' => ['sometimes', 'string', Rule::in(['active', 'inactive'])],
        ];
    }
}
