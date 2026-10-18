<?php

namespace App\Http\Requests;

use App\Enums\Raridade;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClasseRequest extends FormRequest
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
            ],

            'descricao' => [
                'required',
                'string',
            ],

            'raridade' => [
                'required',
                Rule::enum(Raridade::class),
            ],
        ];
    }
}
