<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParcelLocationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'latitude' => ['required', 'numeric', 'min:-90', 'max:90'],
            'longitude' => ['required', 'numeric', 'min:-180', 'max:180']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
