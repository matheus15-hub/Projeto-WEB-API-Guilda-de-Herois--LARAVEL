<?php
namespace App\Services;

use App\Models\MissoesCompletada;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class MissoesCompletadasService{

    public function __construct(
        private MissaoService $missaoService,
        private MonstroService $monstroService,
        private AventureiroService $aventureiro_service
        ) {}
        
        public function createMissaoCompleta(array $dados): MissoesCompletada
        {
            $missaoCompleta = new MissoesCompletada();
            
            $missaoCompleta->id_aventureiro = $dados['id_aventureiro'];
            $missaoCompleta->id_missao = $dados['id_missao'];
            
            $missao = $this->missaoService->getMissaoById(
                $missaoCompleta->id_missao
                );
                
                $monstro = $this->monstroService->getMonstroById(
                    $missao->id_monstro
                    );
                    
                    $xp = $monstro->drop_xp * $missao->quantidade_monstro;
                    
                    $missaoCompleta->ouro_recebido = $missao->recompensa_ouro;
                    $missaoCompleta->xp_recebido = $xp;
                    
                    $missaoCompleta->save();
                    
                    return $missaoCompleta;
        }
        public function getMissoesCompletasAll()
        {
            $missoes = DB::table('missoes_completadas as mc')
                ->join('missao as m', 'm.missao_id', '=', 'mc.id_missao')
                ->join('monstro as mt', 'mt.monstro_id', '=', 'm.id_monstro')
                ->join('aventureiro as a', 'a.aventureiro_id', '=', 'mc.id_aventureiro')
                ->join('classe as cl', 'cl.classe_id', '=', 'a.id_classe')
                ->select(
                    'm.missao_id as id_missao',
                    'm.missao_nome as nome_missao',
                    'mt.monstro_nome as monstro',
                    'm.quantidade_monstro as quantidade',
                    'm.recompensa_ouro as recompensa',
                    'mc.xp_recebido as xp',
                    'a.aventureiro_id as id_aventureiro',
                    'a.aventureiro_nome as aventureiro',
                    'cl.classe_nome as classe'
                )
                ->get();

            return $missoes;
        }

        public function getMissaoAventureiroById(int $idMissao, int $idAve)
        {
             $missoes = DB::table('missoes_completadas as mc')
                ->join('missao as m', 'm.missao_id', '=', 'mc.id_missao')
                ->join('monstro as mt', 'mt.monstro_id', '=', 'm.id_monstro')
                ->join('aventureiro as a', 'a.aventureiro_id', '=', 'mc.id_aventureiro')
                ->join('classe as cl', 'cl.classe_id', '=', 'a.id_classe')
                ->select(
                    'm.missao_id as id_missao',
                    'm.missao_nome as nome_missao',
                    'mt.monstro_nome as monstro',
                    'm.quantidade_monstro as quantidade',
                    'm.recompensa_ouro as recompensa',
                    'mc.xp_recebido as xp',
                    'a.aventureiro_id as id_aventureiro',
                    'a.aventureiro_nome as aventureiro',
                    'cl.classe_nome as classe'
                )
                ->where('mc.id_aventureiro', '=', $idAve)
                ->orwhere('mc.id_missao', '=', $idMissao)
                ->get();

            return $missoes;
        }

        public function upadateMissaoAventureirotaById(int $idMissao, int $idAve, array $dados){
           
            //vereficando se existe
            $this->missaoService->getMissaoById($idMissao);
            $this->aventureiro_service->getAventureiroByID($idAve);
            
           $dadosatualizados = [];
           if(isset($dados['id_aventureiro'])){
            $dadosatualizados['id_avetureiro'] = $dados['id_aventureiro'];
           }
            if(empty($dadosatualizados)){
                return 0;
            }

            //atualizando
            DB::table('missoes_completas')
            ->where('mc.id_aventureiro', '=', $idAve)
            ->where('mc.id_missao', '=', $idMissao)
            ->update($dadosatualizados);

                    
            return DB::table('missoes_completas')
            ->where('mc.id_aventureiro', '=', $idAve)
            ->where('mc.id_missao', '=', $idMissao)
            ->first();
        }

        public function deleteMissaoAventureiroById(int $idMissao, int $idAve){
            $this->missaoService->getMissaoById($idMissao);
            $this->aventureiro_service->getAventureiroByID($idAve);
            DB::table('missoes_completas')
            ->where('mc.id_aventureiro', '=', $idAve)
            ->where('mc.id_missao', '=', $idMissao)
            ->delete();

        }




}