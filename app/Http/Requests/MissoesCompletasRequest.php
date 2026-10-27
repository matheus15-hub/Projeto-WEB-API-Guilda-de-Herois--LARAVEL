<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class MissoesCompletasRequest extends FormRequest
{
 
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
            'id_missao' =>[
                'required',
                'integer',
                'min:1',
                'exists:missao,missao_id'
            ],
            'id_aventureiro'=>[
                'required',
                'integer',
                'min:1',
                'exists:aventureiro,aventureiro_id'
            ]
        ];
    }
}
