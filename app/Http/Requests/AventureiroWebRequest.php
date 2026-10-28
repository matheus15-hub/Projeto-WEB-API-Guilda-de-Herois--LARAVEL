<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AventureiroWebRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'min:3', 'max:150', "regex:/^[\p{L}\p{N}\s'-]+$/u"],
            'idade' => ['required', 'integer', 'min:1', 'max:1000'],
            'experiencia' => ['nullable', 'integer', 'min:0'],
            'ouro' => ['nullable', 'integer', 'min:0'],
            'id_classe' => ['required', 'integer', 'exists:classe,classe_id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nome' => 'nome',
            'idade' => 'idade',
            'experiencia' => 'experiencia',
            'ouro' => 'ouro',
            'id_classe' => 'classe',
        ];
    }
    public function messages(): array
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'integer' => 'O campo :attribute deve ser um número inteiro.',
            'min.string' => 'O campo :attribute deve ter pelo menos :min caracteres.',
            'max.string' => 'O campo :attribute deve ter no máximo :max caracteres.',
            'min.numeric' => 'O campo :attribute deve ser no mínimo :min.',
            'regex' => 'O nome tem caracteres inválidos.',
            'exists' => 'Selecione uma classe válida.',
        ];
    }
}