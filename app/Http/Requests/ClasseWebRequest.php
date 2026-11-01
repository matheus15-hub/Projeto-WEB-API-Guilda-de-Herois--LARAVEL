<?php

namespace App\Http\Requests;

use App\Enums\Raridade;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClasseWebRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'min:3', 'max:150'],
            'descricao' => ['required', 'string', 'max:1000'],
            'raridade' => ['required', Rule::enum(Raridade::class)],
        ];
    }

    public function attributes(): array
    {
        return [
            'nome' => 'nome',
            'descricao' => 'descrição',
            'raridade' => 'raridade',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'min.string' => 'O campo :attribute deve ter pelo menos :min caracteres.',
            'max.string' => 'O campo :attribute deve ter no máximo :max caracteres.',
            'enum' => 'Selecione uma raridade válida.',
        ];
    }
}