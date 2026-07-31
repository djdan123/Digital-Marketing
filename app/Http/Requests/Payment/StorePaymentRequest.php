<?php

namespace App\Http\Requests\Payment;

use App\DTOs\Payment\ProcessPaymentDTO;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'advertiser_id' => ['required', 'integer', 'exists:advertisers,id'],
            'campaign_id' => ['required', 'integer', 'exists:campaigns,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
            'payment_method' => ['required', 'string', Rule::in(array_map(fn($value) => $value->value, PaymentMethod::cases()))],
            'status' => ['sometimes', 'string', Rule::in(array_map(fn($value) => $value->value, PaymentStatus::cases()))],
            'reference' => ['nullable', 'string', 'max:255'],
            'metadata' => ['nullable', 'array'],
        ];
    }

    public function validatedDTO(): ProcessPaymentDTO
    {
        return ProcessPaymentDTO::fromArray($this->validated());
    }
}
