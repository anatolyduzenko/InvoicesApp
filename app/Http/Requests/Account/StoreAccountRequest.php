<?php

namespace App\Http\Requests\Account;

use App\Enums\Currency;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAccountRequest extends FormRequest
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
            'intermediary' => 'required|string|max:255',
            'institution' => 'required|string|max:255',
            'beneficiary' => 'required|string|max:255',
            'account' => 'required|string|max:100',
            'currency' => [Rule::enum(Currency::class)],
        ];
    }
}
