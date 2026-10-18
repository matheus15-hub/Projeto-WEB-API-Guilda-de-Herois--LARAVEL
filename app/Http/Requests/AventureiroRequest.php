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

            'idade' => [
                'required',
                'integer',
                'min:14',
            ],

            'experiencia' => [
                'required',
                'integer',
                'min:0',
            ],

            'ouro' => [
                'required',
                'integer',
                'min:0',
            ],

            'classe_id' => [
                'required',
                'integer',
                'min:1',
                'exists:Joao_Victor_trabalho_BD.classe,id_classe',
            ],

            'guilda_id' => [
                'required',
                'integer',
                'min:1',
                'exists:Joao_Victor_trabalho_BD.guilda,id_guilda',
            ],
        ];
        
    }
}
