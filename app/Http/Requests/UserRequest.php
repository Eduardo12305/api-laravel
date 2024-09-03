<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
            'cpf' => 'required|string',
            'role' => 'required|string|in:superadmin,admin,cliente',

        ];
    }

    public function messages() {
        return [
            'password.confirmed' => 'A confirmção de senha não corresponde.',
            'cpf.unique' => 'Ja existe um usuario com este CPF',
        ];
    }
}
