<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreFinanceRecordRequest extends FormRequest
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
            "finance_category_id" => "required|integer|exists:finance_categories,id",
            "amount" => "required|numeric|min:0",
            "date" => "required|date|before_or_equal:today",
            "description" => "nullable|string|max:500",
        ];
    }

    public function messages()
    {
        return [
            "finance_category_id.required" => "Please select a finance category.",
            "finance_category_id.exists" => "The selected finance category does not exist.",
            "amount.required" => "Please enter the amount.",
            "amount.numeric" => "The amount must be a valid number.",
            "amount.min" => "The amount cannot be negative.",
            "date.required" => "Please enter the transaction date.",
            "date.date" => "The transaction date must be a valid date.",
            "date.before_or_equal" => "The transaction date cannot be in the future.",
            "description.max" => "The description cannot exceed 500 characters.",
        ];
    }

}
