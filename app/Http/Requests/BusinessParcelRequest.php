<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BusinessParcelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public
    function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public
    function rules(): array
    {
        return [
            'source_name' => ['required', 'string', 'max:64'],
            'source_address' => ['required', 'string', 'max:2000'],
            'source_phone' => ['required', 'string', 'max:11'],
            'source_latitude' => ['required', 'numeric', 'min:-90', 'max:90'],
            'source_longitude' => ['required', 'numeric', 'min:-180', 'max:180'],
            'destination_name' => ['required', 'string', 'max:64'],
            'destination_address' => ['required', 'string', 'max:2000'],
            'destination_phone' => ['required', 'string', 'max:11'],
            'destination_latitude' => ['required', 'numeric', 'min:-90', 'max:90'],
            'destination_longitude' => ['required', 'numeric', 'min:-180', 'max:180'],
        ];
    }
}
