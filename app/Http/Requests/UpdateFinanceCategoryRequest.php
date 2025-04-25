<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFinanceCategoryRequest extends CustomFormRequest
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
        $rules = [
            "name" => "required|max:255|min:3",
            "finance_type_id" => "required|integer|exists:finance_types,id",
            "color" => "required|max:7",
        ];

        if (auth()->user()->hasRole(AppConstant::ROLE_ADMIN)) {
            $rules["user_id"] = "required|integer|exists:users,id";
        }
        return $rules;
    }

    public function messages()
    {
        return [
            "user_id.required" => "The user field is required for admin users.",
            "user_id.exists" => "The selected user does not exist.",
            "name.required" => "Please enter a name",
            "name.max" => "Name must consist of at maximum 255 characters",
            "name.min" => "Name must consist of at least 3 characters",
            "finance_type_id.min" => "Name must consist of at least 3 characters",
            "finance_type_id.required" => "Please select finance type",
            "finance_type_id.exists" => "The selected finance type does not exist.",
        ];
    }
}
