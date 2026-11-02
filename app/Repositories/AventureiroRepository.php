<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class AventureiroRepository
{
    public function all()
    {
        return DB::table('aventureiro')->get();
    }

    public function findById(int $id)
    {
        return DB::table('aventureiro')
            ->where('aventureiro_id', $id)
            ->firstOrFail();
    }

    public function create(array $dados)
    {
        $id = DB::table('aventureiro')->insertGetId([
            'aventureiro_nome' => $dados['nome'],
            'idade' => $dados['idade'],
            'experiencia' => $dados['experiencia'] ?? 0,
            'ouro' => $dados['ouro'] ?? 1,
            'id_classe' => $dados['id_classe'],
            'id_guilda' => $dados['id_guilda']
        ], 'aventureiro_id');

        return $this->findById($id);
    }

    public function update(int $id, array $dados)
    {
        $dadosAtualizados = [];

        if (isset($dados['nome'])) {
            $dadosAtualizados['aventureiro_nome'] = $dados['nome'];
        }

        if (isset($dados['idade'])) {
            $dadosAtualizados['idade'] = $dados['idade'];
        }

        if (isset($dados['experiencia'])) {
            $dadosAtualizados['experiencia'] = $dados['experiencia'];
        }

        if (isset($dados['ouro'])) {
            $dadosAtualizados['ouro'] = $dados['ouro'];
        }

        if (isset($dados['id_classe'])) {
            $dadosAtualizados['id_classe'] = $dados['id_classe'];
        }

        if (isset($dados['id_guilda'])) {
            $dadosAtualizados['id_guilda'] = $dados['id_guilda'];
        }

        if (!empty($dadosAtualizados)) {
            DB::table('aventureiro')
                ->where('aventureiro_id', $id)
                ->update($dadosAtualizados);
        }

        return $this->findById($id);
    }

    public function delete(int $id): void
    {
        DB::table('aventureiro')
            ->where('aventureiro_id', $id)
            ->delete();
    }

    public function transferirAventureiro(int $idAventureiro, ?int $novaGuildaId): void
    {
        DB::statement('CALL pr_transferir_aventureiro(?, ?)', [
            $idAventureiro,
            $novaGuildaId
        ]);
    }
}