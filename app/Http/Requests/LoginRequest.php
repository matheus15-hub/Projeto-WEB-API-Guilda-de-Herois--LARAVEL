<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [    
            'name' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    public function messages(): array
{
    return [
        'name.required'     => 'Informe o nome.',
        'password.required' => 'Informe a senha.',
    ];
}
}