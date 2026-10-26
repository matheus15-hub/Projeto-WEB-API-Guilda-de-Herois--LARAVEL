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
