<?php
namespace App\Repositories;

use App\Interfaces\ClasseRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Override;

class ClasseRepository implements ClasseRepositoryInterface{

    public function findAll(){
        return DB::table('classe')->get();
    }
    public function findById(int $id)
    {
        return DB::table('classe')
        ->where('classe_id', $id)
        ->firstOrFail();
    }
    public function create(array $dados)
    {
        $id = DB::table('classe')->insertGetId(
            [
                'classe_nome' => $dados['nome'],
                'descricao' => $dados['descricao'],
                'raridade' => $dados['raridade']
            ],'classe_id'
        );
        return $this->findById($id);
    } 
    
    public function update(int $id, array $dados)
    {
        $dadosatualizados = [];
        if(isset($dados['nome'])){
            $dadosatualizados['classe_nome'] = $dados['nome'];
        }
        if(isset($dados['descricao'])){
            $dadosatualizados['descricao'] = $dados['descricao'];
        }
        if(isset($dados['raridade'])){
            $dadosatualizados['raridade'] = $dados['raridade'];
        }

        if(!empty($dadosatualizados)){

            DB::table('classe')
            ->where('classe_id',$id)
            ->update($dadosatualizados);
            }

        return $this->findById($id);
    }

    public function delete(int $id)
    {
        DB::table('classe')
        ->where('classe_id', $id)
        ->delete();
    }
}