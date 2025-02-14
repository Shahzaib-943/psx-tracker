<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePortfolioRequest extends CustomFormRequest
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
            "name" => "required|max:255|min:3",
            "description" => "nullable|max:255|min:3",
            "user_id" => [
                "nullable",
                "exists:users,id",
            ],
        ];
    }

    public function messages()
    {
        return [
            "user_id.exists" => "The selected user does not exist.",
            "name.required" => "Please enter a name",
            "name.max" => "Name must consist of at maximum 255 characters",
            "name.min" => "Name must consist of at least 3 characters",
        ];
    }
}
