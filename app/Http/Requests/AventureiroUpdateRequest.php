<?php

namespace App\Http\Requests;

use Illuminate\Validation\Validator;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AventureiroUpdateRequest extends FormRequest
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
                'sometimes',
                'string',
                'min:4',
                'max:150',
                "regex:/^[\p{L}\s'-]+$/u",
            ],
            'idade'=> [
                'sometimes',
                'integer',
                'min:14'
            ],

            'id_classe' => [
                'sometimes',
                'integer',
                'min:1',
                'exists:classe,classe_id',
            ],

            'id_guilda' => [
                'sometimes',
                'integer',
                'min:1',
                'exists:guilda,guilda_id',
            ],
         
        ];
    }

     public function messages(): array
    {
        return [
            // Nome
            'nome.string' => 'O nome deve ser um texto.',
            'nome.min' => 'O nome deve possuir pelo menos 4 caracteres.',
            'nome.max' => 'O nome não pode ultrapassar 150 caracteres.',
            'nome.regex' => 'O nome possui caracteres inválidos.',

            // Idade
            'idade.integer' => 'A idade deve ser um número inteiro.',
            'idade.min' => 'O aventureiro deve ter pelo menos 14 anos.',

            // Classe
            'id_classe.integer' => 'O ID da classe deve ser um número inteiro.',
            'id_classe.min' => 'O ID da classe deve ser maior que zero.',
            'id_classe.exists' => 'A classe informada não existe.',

            // Guilda
            'id_guilda.integer' => 'O ID da guilda deve ser um número inteiro.',
            'id_guilda.min' => 'O ID da guilda deve ser maior que zero.',
            'id_guilda.exists' => 'A guilda informada não existe.',
        ];
    }

    // vamos vereficar se algum parametro que não deve ser alterado esta sendo alterado
    //caso esteja vamos retornar um nome falando o nome do parametro e que não pode ser alterado
    public function withValidator(Validator $validator) :void
    {
        $validator->after(function(Validator $validator){
            $camposPermetidos =[
                'nome',
                'idade',
                'id_classe',
                'id_guilda',
            ];

            // Essa linha nos permite pegar o que o usuario mandou, mas pegar apenas o que foi definido na array
            $camposEnviado = array_keys($this->all());

            // aqui pegamos os valores que estão na primeira array, mas não estão na segunda
            $camposInvalidos = array_diff(
                $camposEnviado,
                $camposPermetidos
            );

            foreach($camposInvalidos as $campo){
                $validator->errors()->add(
                    $campo,
                    "O campo '{$campo}' não pode ser alterado."
                
        );
            }
        });
    }
}
