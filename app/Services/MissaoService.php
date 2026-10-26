<?php
namespace App\Services;

use App\Models\Missao;
use Illuminate\Database\Eloquent\Collection;

class MissaoService{

    public function createMissao(array $dados) : Missao
    {
        $missao = new Missao();

        $missao->missao_nome = $dados['nome'];
        $missao->recompensa_ouro = $dados['recompensa'];
        $missao->id_monstro = $dados['id_monstro'];
        $missao->quantidade_monstro = $dados['quantidade_monstros'];
        $missao->dificuldade = $dados['dificuldade'];

        $missao->save();

        return $missao->refresh();

    }

    public function getMissaoById(int $id) : Missao
    {
        return Missao::findOrFail($id);    
    }
    public function getMissaoAll() : Collection
    {
        return Missao::all();   
    }
    public function updateMissaoById(int $id, array $dados) : Missao
    {
        $missao = $this->getMissaoById($id);

        if(isset($dados['nome'])){
            $missao->missao_nome = $dados['nome'];
        }
        if(isset($dados['recompensa'])){
             $missao->recompensa_ouro = $dados['recompensa'];
        }
        if(isset($dados['id_monstro'])){
             $missao->id_monstro = $dados['id_monstro'];
        }
        if(isset($dados['quantidade_monstros'])){
            $missao->quantidade_monstro = $dados['quantidade_monstros'];
        }
        if(isset($dados['dificuldade'])){
             $missao->dificuldade = $dados['dificuldade'];
        }
       
        $missao->save();

        return $missao->refresh();
    }
    public function deleteMissaoById($id) : void
    {
        $missao = $this->getMissaoById($id);
        $missao->delete();
    }
}