<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegistroRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }
    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => strtolower(trim((string)$this->input('email'))),
        ]);
    }
    public function rules(): array
    {
        return [
        'name'      => ['required', 'string', 'min:3', 'max:255', 'unique:users,name'],
        'id_guilda' => ['required', 'integer', 'exists:guilda,guilda_id'],
        'password'  => ['required', 'confirmed', Password::min(8)],
        ];
    }  

    public function attributes(): array
    {
        return [
        'name'      => 'nome',
        'id_guilda' => 'guilda',
        'password'  => 'senha',
        ];
    }
    public function messages(): array
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'email' => 'Informe um e-mail válido.',
            'unique' => 'Este e-mail já está cadastrado.',
            'exists' => 'Selecione uma guilda válida.',
            'min.string' => 'O campo :attribute deve ter pelo menos :min caracteres.',
            'max.string' => 'O campo :attribute deve ter no máximo :max caracteres.',
            'password.confirmed' => 'A confirmação da senha não confere.',
            'password.min' => 'A senha deve ter pelo menos :min caracteres.',
        ];
    }
}
