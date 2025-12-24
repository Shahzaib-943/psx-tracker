<?php

namespace App\Http\Requests;

class StorePortfolioHoldingRequest extends CustomFormRequest
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
     */
    public function rules(): array
    {
        return [
            'portfolio' => ['required', 'exists:portfolios,public_id'],
            'stock' => ['required', 'exists:stocks,public_id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'price_per_share' => ['required', 'numeric', 'min:0.01'],
            'total_investment' => ['nullable', 'numeric', 'min:0.01'],
            'make_deductions' => ['nullable', 'boolean'],
            'transaction_date' => ['date'],
        ];
    }
}
