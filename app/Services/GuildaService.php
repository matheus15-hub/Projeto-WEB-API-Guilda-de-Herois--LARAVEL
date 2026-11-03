<?php

namespace App\Services;

use App\Interfaces\GuildaRepositoryInterface;
use App\Models\Guilda;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class GuildaService{
    
    public function __construct(private GuildaRepositoryInterface $repository){ }
    public function createGuilda(array $dados)  
    {
        return $this->repository->create($dados);
    }

    public function getGuildaById(int $id) 
    {
        return Guilda::findOrFail($id);
    }

    public function getGuildaAll() : Collection
    {
        return $this->repository->all();
    }

    public function updateGuildaById(int $id , array $dados) 
    {
        return $this->repository->update($id, $dados);
    }

    public function deleteGuildaById(int $id)
    {
        $this->getGuildaById($id);
        $this->repository->delete($id);
    }

    public function listarMissoesCompletadasGuilda(int $idGuilda){
        return DB::table('missoes_completadas as mc')
            ->join(
                'aventureiro as a',
                'a.aventureiro_id',
                '=',
                'mc.id_aventureiro'
            )
            ->where('a.id_guilda', $idGuilda)
            ->select(
                'a.aventureiro_nome',
                'mc.id_missao',
                'mc.ouro_recebido',
                'mc.xp_recebido'
            )
            ->get();
    }

    public function calcularOuroGuilda(int $idGuilda): int{
        $resultado = DB::selectOne(
            'select fn_calcular_ouro_recebido(?) as total',
            [$idGuilda]
        );

        return (int) $resultado->total;
    }

    public function listarMissoesCompletadasGuildda(int $idGuilda){
        return DB::select(
            'select * from fn_listar_missoes_completadas(?)',
            [$idGuilda]
        );
    }
}