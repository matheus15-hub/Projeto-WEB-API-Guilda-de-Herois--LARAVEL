<?php

namespace App\Services;

use App\Models\Guilda;
use Illuminate\Database\Eloquent\Collection;

class GuildaService{

    public function createGuilda(array $dados) : Guilda
    {
        $guilda = new Guilda();

        $guilda->guilda_nome = $dados['nome'];
        $guilda->lema = $dados['lema'];
        
        $guilda->save();
        return  $guilda->refresh();
    }

    public function getGuildaById(int $id) : Guilda
    {
        return Guilda::findOrFail($id);
    }

    public function getGuildaAll() : Collection
    {
        return Guilda::all();
    }

    public function updateGuildaById(int $id , array $dados) : Guilda
    {
        $guilda = $this->getGuildaById($id);

        if(isset($dados['nome'])){

            $guilda->guilda_nome = $dados['nome'];
        }
        if(isset($dados['lema']))
        $guilda->lema = $dados['lema'];

        $guilda->save();
        return  $guilda;
    }

    public function deleteGuildaById(int $id) : void{
        $guilda = $this->getGuildaById($id);
        $guilda->delete();
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