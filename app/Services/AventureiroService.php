<?php

namespace App\Services;

use App\Models\Aventureiro;
use Illuminate\Database\Eloquent\Collection;

class AventureiroService{


    public function createAventureiro(array $dados) : Aventureiro{

        $aventureiro = new Aventureiro();

        $aventureiro->nome = $dados['nome'];
        $aventureiro->idade = $dados['idade'];
        $aventureiro->experiencia= $dados['experiencia'];
        $aventureiro->ouro = $dados['ouro'];
        $aventureiro->id_classe = $dados['id_classe'];
        $aventureiro->id_guilda = $dados['id_guilda'];

        $aventureiro->save();

        return $aventureiro;
    }

    public function getAventureiroByID(int $id) : Aventureiro {
        return  Aventureiro::findOrFail($id);
    }
    public function getAventureiroAll() : Collection 
    {
        return Aventureiro::all();
    }
    public function updateAventureiroById(int $id , array $dados) : Aventureiro
    {   
        $aventureiro = $this->getAventureiroByID($id);
        $aventureiro->nome = $dados['nome'];
        $aventureiro->id_classe = $dados['id_classe'];
        $aventureiro->id_guilda = $dados['id_guilda'];

        $aventureiro ->save();

        return $aventureiro;
    }

    public function deleteAventureiroById(int $id) : void
    {
        $aventureiro = $this->getAventureiroByID($id);
        $aventureiro->delete();
    }

}