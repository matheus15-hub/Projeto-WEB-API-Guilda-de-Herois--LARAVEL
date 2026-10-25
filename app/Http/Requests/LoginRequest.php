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
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function messages(): array 
    {
            return [
            'email.required' => 'Informe o Email',
            'email.email'       => 'Informe um e-mail valido.',
            'password.required' => 'Informe sua senha',
            ];
    }
}
