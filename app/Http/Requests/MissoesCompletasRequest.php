<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MissoesCompletasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_missao' => [
                'required',
                'integer',
                'min:1',
                'exists:missao,missao_id'
            ],

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
            // Missão
            'id_missao.required' => 'O ID da missão é obrigatório.',
            'id_missao.integer' => 'O ID da missão deve ser um número inteiro.',
            'id_missao.min' => 'O ID da missão deve ser maior que zero.',
            'id_missao.exists' => 'A missão informada não existe.',

            // Aventureiro
            'id_aventureiro.required' => 'O ID do aventureiro é obrigatório.',
            'id_aventureiro.integer' => 'O ID do aventureiro deve ser um número inteiro.',
            'id_aventureiro.min' => 'O ID do aventureiro deve ser maior que zero.',
            'id_aventureiro.exists' => 'O aventureiro informado não existe.',
        ];
    }
}
