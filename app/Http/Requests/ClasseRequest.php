<?php

namespace App\Http\Requests;

use App\Enums\Raridade;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClasseRequest extends FormRequest
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
            ],

            'descricao' => [
                $regra,
                'string',
            ],

            'raridade' => [
                $regra,
                Rule::enum(Raridade::class),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            // Nome
            'nome.required' => 'O nome da classe é obrigatório.',
            'nome.string' => 'O nome da classe deve ser um texto.',
            'nome.min' => 'O nome da classe deve possuir pelo menos 4 caracteres.',
            'nome.max' => 'O nome da classe não pode ultrapassar 150 caracteres.',

            // Descrição
            'descricao.required' => 'A descrição da classe é obrigatória.',
            'descricao.string' => 'A descrição deve ser um texto.',

            // Raridade
            'raridade.required' => 'A raridade da classe é obrigatória.',
            'raridade.enum' => 'A raridade informada é inválida. Escolha uma raridade existente.',
        ];
    }
}
