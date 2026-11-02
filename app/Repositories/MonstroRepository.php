<?php

namespace App\Repositories;

use App\Interfaces\MonstroRepositoryInterface;
use App\Models\Monstro;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class MonstroRepository implements MonstroRepositoryInterface
{
    public function create(array $dados): Monstro
    {
        $id = DB::table('monstro')->insertGetId([
            'monstro_nome' => $dados['nome'],
            'drop_xp' => $dados['drop_xp']
        ], 'monstro_id');

        return $this->findById($id);
    }

    public function findById(int $id): Monstro
    {
        $dados = DB::table('monstro')
            ->where('monstro_id', $id)
            ->first();

        if (!$dados) {
            abort(404, 'Monstro não encontrado.');
        }

        return new Monstro((array) $dados);
    }

    public function all(): Collection
    {
        return Monstro::hydrate(
            DB::table('monstro')->get()->toArray()
        );
    }

    public function update(int $id, array $dados): Monstro
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
