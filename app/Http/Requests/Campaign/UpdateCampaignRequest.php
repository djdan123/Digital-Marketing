<?php

namespace App\Http\Requests\Campaign;

use App\DTOs\Campaign\UpdateCampaignDTO;
use App\Enums\CampaignStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCampaignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'objective' => ['nullable', 'string', 'max:1000'],
            'status' => ['sometimes', 'string', Rule::in(array_map(fn($value) => $value->value, CampaignStatus::cases()))],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'budget' => ['nullable', 'numeric', 'min:0'],
            'spent' => ['nullable', 'numeric', 'min:0'],
            'targeting' => ['nullable', 'array'],
            'targeting.*' => ['sometimes'],
        ];
    }

    public function validatedDTO(): UpdateCampaignDTO
    {
        return UpdateCampaignDTO::fromArray($this->validated());
    }
}
