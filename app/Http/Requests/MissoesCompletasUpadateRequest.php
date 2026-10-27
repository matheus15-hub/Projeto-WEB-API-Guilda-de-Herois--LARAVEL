<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class MissoesCompletasUpadateRequest extends FormRequest
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
             'id_aventureiro'=>[
                'required',
                'integer',
                'min:1',
                'exists:aventureiro,aventureiro_id'
            ]
        ];    
    }

    public function withValidator(Validator $validator){

        $validator->after(function(Validator $validator){
            $campos_permetidos =[
                'id_aventureiro'
            ];
            $campos_recebidos = array_keys($this->all());

            $campos_invalidos = array_diff(
                $campos_recebidos,
                $campos_permetidos
            );

            foreach($campos_invalidos as $campos){
                $validator->errors()->add(
                    $campos,
                    "O campo {$campos} não pode ser alterados."
                );
            }
        });
    }
}
