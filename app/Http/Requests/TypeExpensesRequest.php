<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TypeExpensesRequest extends FormRequest
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
            'idUser' => 'required|string',
            'name' => 'required|string|max:70',
            'description' => 'required|string|max:255',
            'cor' => 'required|string|max:10',
            'icon' => 'required|string',
        ];
    }
}
