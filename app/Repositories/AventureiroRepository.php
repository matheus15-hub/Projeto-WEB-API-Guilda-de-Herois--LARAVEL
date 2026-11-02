<?php
namespace App\Repositories;

use App\Interfaces\AventureiroRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Override;

class AventureiroRepository implements AventureiroRepositoryInterface
{
    #[Override]
    public function all()
    {
        return DB::table('aventureiro')->get();
    }
    #[Override]
    public function findById(int $id)
    {
        return DB::table('aventureiro')->where('aventureiro_id', $id)
        ->firstOrFail();
    }
    #[Override]
    public function create(array $dados)
    {
        $id = DB::table('aventureiro')->insertGetId(
            [   
            'aventureiro_nome' => $dados['nome'],
            'idade' => $dados['idade'],
            'experiencia'=> $dados['experiencia'],
            'ouro' => $dados['ouro'],
            'id_classe' => $dados['id_classe'],
            'id_guilda' => $dados['id_guilda']
            ], 'aventureiro_id'
        );
        return $this->findById($id);
    }
    #[Override]
    public function upadate(int $id, array $dados)
    {
        $dadosAtualizados =[];

        if(isset($dados['nome'])){
            $dadosAtualizados['aventureiro_nome'] = $dados['nome'];
        }
        if(isset($dados['idade'])){
            $dadosAtualizados['idade']= $dados['idade'];
         }
        if(isset($dados['experiencia'])){
            $dadosAtualizados['experiencia'] = $dados['experiencia'];
        }
        if(isset($dados['ouro'])){
            $dadosAtualizados['ouro'] = $dados['ouro'];
        }
        if(isset($dados['id_classe'])){
            $dadosAtualizados['id_classe'] = $dados['id_classe'];
        }
        if(isset($dados['id_guilda'])){
            $dadosAtualizados['id_guilda'] = $dados['id_guilda'];
            }

        if(!empty($dadosAtualizados)){
            DB::table('aventureiro')
            ->where('aventureiro_id', $id)
            ->update($dadosAtualizados);
        }

        return $this->findById($id);
    }

    #[Override]
    public function delete(int $id)
    {
        DB::table('aventureiro')
        ->where('aventureiro_id', $id)
        ->delete();
    }

}