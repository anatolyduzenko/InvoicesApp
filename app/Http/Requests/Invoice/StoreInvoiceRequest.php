<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
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
            'account_id' => 'required|exists:accounts,id',
            'company_id' => 'required|exists:companies,id',
            'customer_id' => 'required|exists:customers,id',
            'due_date' => 'required|date',
            'issue_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|numeric',
            'items.*.description' => 'required|string|max:255',
            'items.*.qty' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.unit' => 'required|string|max:255',
            'items.*.amount' => 'required|numeric|min:0',
            'number' => 'required|string',
            'total_amount' => 'required|numeric|min:0',
        ];
    }
}
