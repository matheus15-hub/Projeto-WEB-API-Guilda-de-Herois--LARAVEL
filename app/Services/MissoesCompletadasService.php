<?php
namespace App\Services;

use App\Models\MissoesCompletada;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Missao;
use App\Interfaces\MissoesCompletadasRepositoryInterface;
use App\Models\Aventureiro;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MissoesCompletadasService{

    public function __construct(
            private MissaoService $missaoService,
            private MonstroService $monstroService,
            private AventureiroService $aventureiro_service,
            private MissoesCompletadasRepositoryInterface $repository
        ) {}
        
        public function createMissaoCompleta(array $dados)
        {
            $this->aventureiro_service->getAventureiroByID($dados['id_aventureiro']);

            $missao = $this->missaoService->getMissaoById(
            $dados['id_missao']
            );

            $monstro = $this->monstroService->getMonstroById(
                $missao->id_monstro
            );

            $xp = $monstro->drop_xp * $missao->quantidade_monstro;

            DB::table('missoes_completadas')->insert([
                'id_aventureiro' => $dados['id_aventureiro'],
                'id_missao' => $dados['id_missao'],
                'ouro_recebido' => $missao->recompensa_ouro,
                'xp_recebido' => $xp,
            ]);

            return [
                'id_aventureiro' => $dados['id_aventureiro'],
                'id_missao' => $dados['id_missao'],
                'ouro_recebido' => $missao->recompensa_ouro,
                'xp_recebido' => $xp,
            ];
                    
        }
        public function getMissoesCompletasAll(int $porpagina=10)
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
                ->paginate($porpagina);

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
                ->  where('mc.id_missao', '=', $idMissao)
                ->get();

            return $missoes;
        }

        public function upadateMissaoAventureirotaById(int $idMissao, int $idAve, array $dados){
           
            //vereficando se existe
            $this->missaoService->getMissaoById($idMissao);
            $this->aventureiro_service->getAventureiroByID($idAve);
            
           DB::table('missoes_completadas')
            ->where('id_aventureiro', $idAve)
            ->where('id_missao', $idMissao)
            ->update([
                'id_aventureiro' => $dados['id_aventureiro']
            ]);

        
        return DB::table('missoes_completadas')
            ->where('id_aventureiro', $dados['id_aventureiro'])
            ->where('id_missao', $idMissao)
            ->first();
        }

        public function deleteMissaoAventureiroById(int $idMissao, int $idAve)
        {
            $this->missaoService->getMissaoById($idMissao);
            $this->aventureiro_service->getAventureiroByID($idAve);
            DB::table('missoes_completadas')
            ->where('id_aventureiro', '=', $idAve)
            ->where('id_missao', '=', $idMissao)
            ->delete();

        }

        public function createVariasMissoesCompletas(int $idAventureiro, array $idsMissao): int
        {
            if (! $this->repository->aventureiroExiste($idAventureiro)) {
                throw (new ModelNotFoundException())->setModel(Aventureiro::class, [$idAventureiro]);
            }

            $jaFez = $this->repository->idsJaConcluidos($idAventureiro, $idsMissao);
            $novos = array_values(array_diff($idsMissao, $jaFez));

            if (empty($novos)) {
                return 0;
            }

            $linhas = $this->repository->buscarDadosRecompensaVarias($novos)
                ->map(fn ($m) => [
                    'id_aventureiro' => $idAventureiro,
                    'id_missao'      => $m->missao_id,
                    'ouro_recebido'  => $m->recompensa_ouro,
                    'xp_recebido'    => $this->calcularXp($m->drop_xp, $m->quantidade_monstro),
                ])
                ->values()
                ->all();

            $this->repository->inserirVarias($linhas);

            return count($linhas);
        }

        public function calcularXp(int $dropXp, int $quantidade): int
        {
            return $dropXp * $quantidade;
        }

    public function listarConcluidasPorAventureiro(int $idAventureiro)
    {
        return $this->repository->listarConcluidasPorAventureiro($idAventureiro);
    }

    public function listarDisponiveisPorAventureiro(int $idAventureiro)
    {
        return $this->repository->listarDisponiveisPorAventureiro($idAventureiro);
    }

    public function aventureiroJaConcluiu(int $idAventureiro, int $idMissao): bool
    {
        return $this->repository->aventureiroJaConcluiu($idAventureiro, $idMissao);
    }
}