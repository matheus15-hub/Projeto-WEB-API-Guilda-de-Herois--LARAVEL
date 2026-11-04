<?php

namespace App\Repositories;

use App\Interfaces\MissoesCompletadasRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Override;

class MissoesCompletadasRepository implements MissoesCompletadasRepositoryInterface 
{
    public function aventureiroExiste(int $idAventureiro): bool
    {
        return DB::table('aventureiro')
            ->where('aventureiro_id', $idAventureiro)
            ->exists();
    }    

    #[Override]
    public function idsJaConcluidos(int $idAventureiro, array $idsMissao): array
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

    public function listarConcluidasPorAventureiro(int $idAventureiro)
    {
        return DB::table('missoes_completadas as mc')
            ->join('missao as m', 'm.missao_id', '=', 'mc.id_missao')
            ->where('mc.id_aventureiro', $idAventureiro)
            ->orderBy('m.missao_nome')
            ->get([
                'm.missao_id',
                'm.missao_nome',
                'm.dificuldade',
                'mc.ouro_recebido',
                'mc.xp_recebido'
            ]);
    }

    public function listarDisponiveisPorAventureiro(int $idAventureiro)
    {
        return DB::table('missao as m')
            ->leftJoin('monstro as mt', 'mt.monstro_id', '=', 'm.id_monstro')
            ->whereNotIn('m.missao_id', function ($query) use ($idAventureiro) {
                $query->select('id_missao')
                    ->from('missoes_completadas')
                    ->where('id_aventureiro', $idAventureiro);
            })
            ->orderBy('m.missao_nome')
            ->select(
                'm.*',
                'mt.monstro_nome'
            )
            ->get();
    }

    public function aventureiroJaConcluiu(int $idAventureiro, int $idMissao): bool
    {
        return DB::table('missoes_completadas')
            ->where('id_aventureiro', $idAventureiro)
            ->where('id_missao', $idMissao)
            ->exists();
    }
}