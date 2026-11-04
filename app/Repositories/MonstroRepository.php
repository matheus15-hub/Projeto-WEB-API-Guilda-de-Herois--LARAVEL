<?php

namespace App\Repositories;

use App\Interfaces\MonstroRepositoryInterface;
use App\Models\Monstro;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use stdClass;

class MonstroRepository implements MonstroRepositoryInterface
{
    public function create(array $dados) : stdClass
    {
        $id = DB::table('monstro')->insertGetId([
            'monstro_nome' => $dados['nome'],
            'drop_xp' => $dados['drop_xp']
        ], 'monstro_id');

        return $this->findById($id);
    }

    public function findById(int $id) : stdClass
    {
        $dados = DB::table('monstro')
            ->where('monstro_id', $id)
            ->first();

        if (!$dados) {
            abort(404, 'Monstro não encontrado.');
        }

        return $dados;
    }

    public function all() : Collection
    {
        return DB::table('monstro')->get();
    }

    public function update(int $id, array $dados): stdClass
    {
        $atualizacao = [];

        if (isset($dados['nome'])) {
            $atualizacao['monstro_nome'] = $dados['nome'];
        }

        if (isset($dados['drop_xp'])) {
            $atualizacao['drop_xp'] = $dados['drop_xp'];
        }

        if (!empty($atualizacao)) {
            DB::table('monstro')
                ->where('monstro_id', $id)
                ->update($atualizacao);
        }

        return $this->findById($id);
    }

    public function delete(int $id): void
    {
        $this->findById($id);

        DB::table('monstro')
            ->where('monstro_id', $id)
            ->delete();
    }
}
