<?php

namespace App\Http\Requests;

use App\Enums\Raridade;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ClasseWebRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome'        => ['nullable', 'string', 'max:50'],
            'classe_nome' => ['nullable', 'string', 'max:50'],
            'descricao'   => ['nullable', 'string', 'max:255'],
            'raridade'    => ['required', new Enum(Raridade::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'raridade.required' => 'Selecione uma raridade válida.',
        ];
    }
}