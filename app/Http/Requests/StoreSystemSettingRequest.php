<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSystemSettingRequest extends CustomFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'market_opening_time' => 'required|date_format:H:i',
            'market_closing_time' => 'required|date_format:H:i',
        ];
    }

    public function messages(): array
    {
        return [
            'market_opening_time.required' => 'The market opening time is required.',
            'market_opening_time.date_format' => 'The market opening time must be in the format HH:MM.',
        ];
    }
}
