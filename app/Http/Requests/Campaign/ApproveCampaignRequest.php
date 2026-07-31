<?php

namespace App\Http\Requests\Campaign;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApproveCampaignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'campaign_id' => ['required', 'integer', 'exists:campaigns,id'],
            'status' => ['required', 'string', Rule::in(['approved', 'rejected'])],
            'comments' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
