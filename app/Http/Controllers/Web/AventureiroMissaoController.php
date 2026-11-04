<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Aventureiro;
use App\Models\Missao;
use App\Services\MissoesCompletadasService;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AventureiroMissaoController extends Controller
{
    public function __construct(private MissoesCompletadasService $missoesCompletadas)
    {

    }
    public function index(Request $request, Aventureiro $aventureiro): View
    {
        $this->daMinhaGuilda($request, $aventureiro);

        $concluidas = $this->missoesCompletadas
            ->listarConcluidasPorAventureiro($aventureiro->aventureiro_id);

        $disponiveis = $this->missoesCompletadas
            ->listarDisponiveisPorAventureiro($aventureiro->aventureiro_id);

        return view('aventureiros.missoes', compact(
            'aventureiro',
            'concluidas',
            'disponiveis'
        ));
    }

    public function store(Request $request, Aventureiro $aventureiro): RedirectResponse
    {
        $this->daMinhaGuilda($request, $aventureiro);

        $dados = $request->validate(
            [
                'missoes' => ['required', 'array', 'min:1'],
                'missoes.*' => ['integer', 'exists:missao,missao_id'],
            ],
            [
                'missoes.required' => 'Selecione pelo menos uma missão.',
                'missoes.min' => 'Selecione pelo menos uma missão.',
                'missoes.*.exists' => 'Uma das missões selecionadas não existe.',
            ]
        );

        $total = 0;
        $falhas = [];

        foreach ($dados['missoes'] as $idMissao) {
            $jaConcluiu = $this->missoesCompletadas
                ->aventureiroJaConcluiu(
                    $aventureiro->aventureiro_id,
                    (int) $idMissao
                );

            if ($jaConcluiu) {
                continue;
            }

            try {
                $this->missoesCompletadas->createMissaoCompleta([
                    'id_aventureiro' => $aventureiro->aventureiro_id,
                    'id_missao' => (int) $idMissao,
                ]);

                $total++;
            } catch (\Throwable $e) {
                report($e);
                $falhas[] = 'Missão ' . $idMissao;
            }
        }

        $resposta = redirect()->route('aventureiros.missoes.index', $aventureiro);

        if ($total > 0) {
            $resposta->with('sucesso', $total . ($total === 1 ? ' missão vinculada.' : ' missões vinculadas.'));
        }

        if ($falhas) {
            $resposta->with('erro', 'Não foi possível vincular: ' . implode(' | ', $falhas));
        }

        return $resposta;
    }
        public function destroy(
            Request $request,
            Aventureiro $aventureiro,
            Missao $missao
        ): RedirectResponse {
            $this->daMinhaGuilda($request, $aventureiro);

            $this->missoesCompletadas->deleteMissaoAventureiroById(
                $missao->missao_id,
                $aventureiro->aventureiro_id
            );

            return back()->with('sucesso', 'Vínculo removido.');
        }

        private function daMinhaGuilda(Request $request, Aventureiro $aventureiro): void
        {
            $guildaId = $request->user()->id_guilda;

            abort_if($guildaId === null, 403, 'Seu usuario não está vinculado a nenhuma Guilda.');
            abort_unless((int) $aventureiro->id_guilda === (int) $guildaId, 404);
        }
}
