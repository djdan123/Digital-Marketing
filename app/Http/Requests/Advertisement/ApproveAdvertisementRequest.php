<?php

namespace App\Http\Requests\Advertisement;

use Illuminate\Foundation\Http\FormRequest;

class ApproveAdvertisementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'comments' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
