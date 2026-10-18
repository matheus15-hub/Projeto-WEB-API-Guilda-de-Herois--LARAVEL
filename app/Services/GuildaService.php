<?php

namespace App\Services;

use App\Models\Guilda;
use Illuminate\Database\Eloquent\Collection;

class GuildaService{

    public function createGuilda(array $dados) : Guilda
    {
        $guilda = new Guilda();

        $guilda->guilda_nome = $dados['nome'];
        $guilda->lema = $dados['lema'];
        
        $guilda->save();
        return  $guilda;
    }

    public function getGuildaById(int $id) : Guilda
    {
        return Guilda::findOrFail($id);
    }

    public function getGuildaAll() : Collection
    {
        return Guilda::all();
    }

    public function updateGuildaById(int $id , array $dados) : Guilda
    {
        $guilda = $this->getGuildaById($id);

        $guilda->guilda_nome = $dados['nome'];
        $guilda->lema = $dados['lema'];

        $guilda->save();
        return  $guilda;
    }

    public function deleteGuildaById(int $id) : void{
        $guilda = $this->getGuildaById($id);
        $guilda->delete();
    }
}