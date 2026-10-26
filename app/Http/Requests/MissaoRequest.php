<?php

namespace App\Http\Requests;

use App\Enums\Dificuldade;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MissaoRequest extends FormRequest
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
                'max:200',
                "regex:/^[\p{L}\s'-]+$/u",
            ],
            'recompensa' => [
                $regra,
                'integer',
                'min:1'
            ],
            'id_monstro' => [
                $regra,
                'integer',
                'min:1',
                'exists:monstro,monstro_id',
            ],
            'quantidade_monstros' =>
            [
                $regra,
                'integer',
                'min:1'
            ],
            'dificuldade' => [
                $regra,
                Rule::enum(Dificuldade::class),
            ]
        ];
    }
}
