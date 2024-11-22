<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpensesRequest extends FormRequest
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
            'idType' => 'required|string',
            'idUser' => 'required|string',
            'name' => 'required|string',
            'value' => 'required|numeric',
            'appellant' => 'required|string',
            'dt_init' => 'date|nullable',
            'dt_update' => 'date|nullable',
            'dt_end' => 'date|nullable',
            'icon' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'idType.required'    => 'O campo idType é obrigatório.',
            'idUser.required'    => 'O campo idUser é obrigatório.',
            'name.required'      => 'O campo name é obrigatório.',
            'value.required'     => 'O campo value é obrigatório.',
            'appellant.required' => 'O campo appellant é obrigatório.',
            'dt_end.required'    => 'O campo dt_end é obrigatório.',
            'icon.required'      => 'O campo icon é obrigatório.',
        ];
    }
}
