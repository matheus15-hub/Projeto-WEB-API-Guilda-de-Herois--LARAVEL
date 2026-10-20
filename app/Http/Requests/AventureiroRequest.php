<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AventureiroRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
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
}
