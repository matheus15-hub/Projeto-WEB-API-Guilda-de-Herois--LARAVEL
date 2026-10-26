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
        $regra = $this->isMethod('post') ? "required" : "sometimes";
        return [
            'nome' => [
                $regra,
                'string',
                'min:4',
                'max:150',
            ],

            'descricao' => [
                $regra,
                'string',
            ],

            'raridade' => [
                $regra,
                Rule::enum(Raridade::class),
            ],
        ];
    }
}
