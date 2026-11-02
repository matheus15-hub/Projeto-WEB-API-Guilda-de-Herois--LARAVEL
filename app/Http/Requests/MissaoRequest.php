<?php

namespace App\Http\Requests;

use App\Enums\Dificuldade;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MissaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $regra = $this->isMethod('post') ? 'required' : 'sometimes';

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

            'quantidade_monstros' => [
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

    public function messages(): array
    {
        return [
            // Nome
            'nome.required' => 'O nome da missão é obrigatório.',
            'nome.string' => 'O nome da missão deve ser um texto.',
            'nome.min' => 'O nome da missão deve possuir pelo menos 4 caracteres.',
            'nome.max' => 'O nome da missão não pode ultrapassar 200 caracteres.',
            'nome.regex' => 'O nome da missão possui caracteres inválidos.',

            // Recompensa
            'recompensa.required' => 'A recompensa da missão é obrigatória.',
            'recompensa.integer' => 'A recompensa deve ser um número inteiro.',
            'recompensa.min' => 'A recompensa deve ser de pelo menos 1 ouro.',

            // Monstro
            'id_monstro.required' => 'O monstro da missão é obrigatório.',
            'id_monstro.integer' => 'O ID do monstro deve ser um número inteiro.',
            'id_monstro.min' => 'O ID do monstro deve ser maior que zero.',
            'id_monstro.exists' => 'O monstro informado não existe.',

            // Quantidade de monstros
            'quantidade_monstros.required' => 'A quantidade de monstros é obrigatória.',
            'quantidade_monstros.integer' => 'A quantidade de monstros deve ser um número inteiro.',
            'quantidade_monstros.min' => 'A quantidade de monstros deve ser pelo menos 1.',

            // Dificuldade
            'dificuldade.required' => 'A dificuldade da missão é obrigatória.',
            'dificuldade.enum' => 'A dificuldade informada é inválida.',
        ];
    }
}
