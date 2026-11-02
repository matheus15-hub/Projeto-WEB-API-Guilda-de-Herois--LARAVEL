<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AventureiroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => [
                'required',
                'string',
                'min:4',
                'max:150',
                "regex:/^[\p{L}\s'-]+$/u",
            ],

            'idade' => [
                'required',
                'integer',
                'min:14'
            ],

            'experiencia' => [
                'required',
                'integer',
                'min:0'
            ],

            'id_classe' => [
                'required',
                'integer',
                'min:1',
                'exists:classe,classe_id',
            ],

            'id_guilda' => [
                'required',
                'integer',
                'min:1',
                'exists:guilda,guilda_id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            // Nome
            'nome.required' => 'O nome do aventureiro é obrigatório.',
            'nome.string' => 'O nome deve ser um texto.',
            'nome.min' => 'O nome deve possuir pelo menos 4 caracteres.',
            'nome.max' => 'O nome não pode ultrapassar 150 caracteres.',
            'nome.regex' => 'O nome possui caracteres inválidos.',

            // Idade
            'idade.required' => 'A idade do aventureiro é obrigatória.',
            'idade.integer' => 'A idade deve ser um número inteiro.',
            'idade.min' => 'O aventureiro deve ter pelo menos 14 anos.',

            // Experiência
            'experiencia.required' => 'A experiência é obrigatória.',
            'experiencia.integer' => 'A experiência deve ser um número inteiro.',
            'experiencia.min' => 'A experiência não pode ser negativa.',

            // Classe
            'id_classe.required' => 'A classe do aventureiro é obrigatória.',
            'id_classe.integer' => 'O ID da classe deve ser um número inteiro.',
            'id_classe.min' => 'O ID da classe deve ser maior que zero.',
            'id_classe.exists' => 'A classe informada não existe.',

            // Guilda
            'id_guilda.required' => 'A guilda do aventureiro é obrigatória.',
            'id_guilda.integer' => 'O ID da guilda deve ser um número inteiro.',
            'id_guilda.min' => 'O ID da guilda deve ser maior que zero.',
            'id_guilda.exists' => 'A guilda informada não existe.',
        ];
    }
}
