<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class planoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => "required|string",
            "value" => "required|numeric",
            "qtd_types_profits" => "required|numeric",
            "qtd_types_expenses" => "required|numeric",
            "exchange_coin" => "required|string",
            "exchange_cryptos" => "required|string",
            "income_forecast" => "required|numeric",
            "grafics" => "required|boolean",
        ];
    }
}
