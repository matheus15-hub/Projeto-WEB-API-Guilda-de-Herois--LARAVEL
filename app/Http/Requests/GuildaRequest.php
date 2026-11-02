<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuildaRequest extends FormRequest
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

            'lema' => [
                'string'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            // Nome
            'nome.required' => 'O nome da guilda é obrigatório.',
            'nome.string' => 'O nome da guilda deve ser um texto.',
            'nome.min' => 'O nome da guilda deve possuir pelo menos 4 caracteres.',
            'nome.max' => 'O nome da guilda não pode ultrapassar 150 caracteres.',

            // Lema
            'lema.string' => 'O lema da guilda deve ser um texto.',
        ];
    }
}
