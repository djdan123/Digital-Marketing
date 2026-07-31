<?php

namespace App\Http\Requests\Advertisement;

use App\DTOs\Advertisement\UploadAdvertisementDTO;
use App\Enums\AdvertisementStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAdvertisementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'campaign_id' => ['required', 'integer', 'exists:campaigns,id'],
            'media_id' => ['required', 'integer', 'exists:media,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'format' => ['nullable', 'string', 'max:255'],
            'status' => ['sometimes', 'string', Rule::in(array_map(fn($value) => $value->value, AdvertisementStatus::cases()))],
            'meta' => ['nullable', 'array'],
            'cost' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function validatedDTO(): UploadAdvertisementDTO
    {
        return UploadAdvertisementDTO::fromArray($this->validated());
    }
}
