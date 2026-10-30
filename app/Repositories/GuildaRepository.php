<?php
namespace App\Repositories;

use App\Interfaces\GuildaRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Override;

class GuildaRepository implements GuildaRepositoryInterface
{
  
    public function all()
    {
        return DB::table('guilda')->get();
    }
   
    public function findById($id)
    {
        return DB::table('guilda')
        ->where('guilda_id', $id)
        ->firstOrFail();
    }

  
    public function create(array $dados)
    {
        $id = DB::table('guilda')->insertGetId(
        [
            'guilda_nome' => $dados['nome'],
            'lema' => $dados['lema']
        ], 'guilda_id'
        );
        return $this->findById($id);
    }
   
    public function update(int $id, array $dados)
    {
        $dadosAtualizado = [];

        if(isset($dados['nome'])){
            $dadosAtualizado['guilda_nome'] = $dados['nome'];
        }
        if(isset($dados['lema'])){
            $dadosAtualizado['lema'] = $dados['lema'];
        }

        if(!empty($dadosAtualizado)){
            DB::table('guilda')
            ->where('guilda_id', $id)
            ->update($dadosAtualizado);
        }
        return $this->findById($id);
    }
    
    public function delete(int $id)
    {
        DB::table('guilda')
        ->where('guilda_id', $id)
        ->delete();
    }
}