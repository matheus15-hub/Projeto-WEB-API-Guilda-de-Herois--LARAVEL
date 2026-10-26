<?php

namespace App\Services;

use App\Models\Monstro;
use Illuminate\Database\Eloquent\Collection;

class MonstroService{


    public function createMonstro(array $dados) : Monstro
    {
        $monstro = new Monstro();

        $monstro->monstro_nome = $dados['nome'];
        $monstro->drop_xp = $dados['drop_xp'];

        $monstro->save();

        return $monstro->refresh();
    }

    public function getMonstroById(int $id) : Monstro
    {
        return Monstro::findOrFail($id);    
   }
   public function getMonstroAll() : Collection {
        return Monstro::all();
   }
   public function updateMonstroById(int $id , array $dados) : Monstro
   {
        $monstro = $this->getMonstroById($id);
        
        // isset ele diz que pode ou não ser nulo, se for nulo ele não entra no if
        // mas se não for nulo ai entra no if
        // isso faz com que no update não é necessario colocar todos os paramentros
        // apenas aquele que serão atualizados.
        if(isset($dados['nome'])){
            $monstro->monstro_nome = $dados['nome'];
        }
        if(isset($dados['drop_xp'])){
            $monstro->drop_xp = $dados['drop_xp'];
        }

        $monstro->save();

        return $monstro;
   }

   public function deleteMonstroById(int $id) : void
   {
         $monstro = $this->getMonstroById($id);
         $monstro->delete();    
   }
}   