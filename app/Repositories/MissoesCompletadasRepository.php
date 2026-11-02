<?php

namespace \App\Repositories;

use App\Interfaces\MissoesCompletadasRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MissoesCompletadasRepository implements MissoesCompletadasRepositoryInterface 
{
    public function aventureiroExiste(int $idAventureiro): bool
    {
        return DB::table('aventureiro')
            ->where('aventureiro_id', $idAventureiro)
            ->exists();
    }    

    public function idJaConcluidos(int $idAventureiro, array $idsMissao): array
    {
        return DB::table('missoes_completadas')
            ->where('id_aventureiro', $idAventureiro)
            ->whereIn('id_missao', $idsMissao)
            ->pluck('id_missao')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    public function buscarDadosRecompensaVarias(array $idsMissao): Collection
    {
        return DB::table('missao as m')
            ->join('monstro as mt', 'mt.monstro_id', '=', 'm.id_monstro')
            ->whereIn('m.missao_id', $idsMissao)
            ->select('m.missao_id', 'm.recompensa_ouro', 'm.quantidade_monstro', 'mt.drop_xp')
            ->get();
    }

    public function inserirVarias(array $linhas): void
    {
        DB::table('missoes_completadas')->insert($linhas);
    }
}