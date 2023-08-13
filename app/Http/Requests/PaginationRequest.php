<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaginationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'page' => ['nullable', 'int', 'min:1'],
            'per_page' => ['nullable', 'int', 'min:1', 'max:50']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
