<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class MissoesCompletasUpadateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_aventureiro' => [
                'required',
                'integer',
                'min:1',
                'exists:aventureiro,aventureiro_id'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'id_aventureiro.required' => 'O ID do aventureiro é obrigatório.',
            'id_aventureiro.integer' => 'O ID do aventureiro deve ser um número inteiro.',
            'id_aventureiro.min' => 'O ID do aventureiro deve ser maior que zero.',
            'id_aventureiro.exists' => 'O aventureiro informado não existe.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {

            $camposPermitidos = [
                'id_aventureiro'
            ];

            $camposRecebidos = array_keys($this->all());

            $camposInvalidos = array_diff(
                $camposRecebidos,
                $camposPermitidos
            );

            foreach ($camposInvalidos as $campo) {
                $validator->errors()->add(
                    $campo,
                    "O campo '{$campo}' não pode ser alterado."
                );
            }
        });
    }
}
