<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MonstroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $regra = $this->isMethod('post') ? 'required' : 'sometimes';

        return [
            'nome' => [
                $regra,
                'string',
                'min:4',
                'max:150',
                "regex:/^[\p{L}\s'-]+$/u",
            ],

            'drop_xp' => [
                $regra,
                'integer',
                'min:1'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            // Nome
            'nome.required' => 'O nome do monstro é obrigatório.',
            'nome.string' => 'O nome do monstro deve ser um texto.',
            'nome.min' => 'O nome do monstro deve possuir pelo menos 4 caracteres.',
            'nome.max' => 'O nome do monstro não pode ultrapassar 150 caracteres.',
            'nome.regex' => 'O nome do monstro possui caracteres inválidos.',

            // Experiência
            'drop_xp.required' => 'A experiência do monstro é obrigatória.',
            'drop_xp.integer' => 'A experiência deve ser um número inteiro.',
            'drop_xp.min' => 'A experiência do monstro deve ser de pelo menos 1 ponto.',
        ];
    }
}
