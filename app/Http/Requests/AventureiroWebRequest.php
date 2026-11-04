<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AventureiroWebRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome'        => ['required', 'string', 'min:3', 'max:150', "regex:/^[\p{L}\p{N}\s'-]+$/u"],
            'idade'       => ['required', 'integer', 'min:1', 'max:1000'],
            'experiencia' => ['nullable', 'integer', 'min:0'],
            'ouro'        => ['nullable', 'integer', 'min:0'],
            'id_classe'   => ['required', 'integer', 'exists:classe,classe_id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nome'        => 'nome',
            'idade'       => 'idade',
            'experiencia' => 'experiência',
            'ouro'        => 'ouro',
            'id_classe'   => 'classe',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required'     => 'O campo nome é obrigatório.',
            'nome.min'          => 'O nome deve ter pelo menos 3 caracteres.',
            'nome.max'          => 'O nome deve ter no máximo 150 caracteres.',
            'nome.regex'        => 'O nome contém caracteres inválidos.',

            'idade.required'    => 'O campo idade é obrigatório.',
            'idade.integer'     => 'A idade deve ser um número inteiro.',
            'idade.min'         => 'A idade deve ser no mínimo 1.',
            'idade.max'         => 'A idade deve ser no máximo 1000.',

            'experiencia.min'   => 'A experiência não pode ser negativa.',
            'ouro.min'          => 'O ouro não pode ser negativo.',

            'id_classe.required' => 'Selecione uma classe.',
            'id_classe.exists'   => 'Selecione uma classe válida.',
        ];
    }
}