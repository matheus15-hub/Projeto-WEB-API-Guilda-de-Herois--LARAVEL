<?php

namespace App\Services;

use App\Enums\Raridade;
use App\Models\Classe;
use Doctrine\DBAL\Query\From;
use Illuminate\Database\Eloquent\Collection;

class ClasseService{
    

    public function createClasse(array $dados): Classe
    {
        $classe = new Classe();

        $classe->classe_nome = $dados['nome'];
        $classe->descricao = $dados['descricao'];
        $classe->raridade = $dados['raridade'];

        $classe->save();

        return $classe;
    }


    public function getClasseById(int $id) : Classe{

    //o findOrFail lança uma erro caso não encontre o id
    return Classe::findOrFail($id);

    }

    public function getClasseAll() : Collection
    {
        return Classe::all();
    }

  
    public function updateClasseById(int $id, array $dados): Classe
    {
        $classe = $this->getClasseById($id);

        $classe->classe_nome = $dados['nome'];
        $classe->descricao = $dados['descricao'];
        $classe->raridade = $dados['raridade'];

        $classe->save();

        return $classe;
    }


    public function deleteClasseById(int $id) : void {
        $classe = Classe::findOrFail($id);
        $classe->delete();
    }
}
