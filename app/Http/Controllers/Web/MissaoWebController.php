<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Aventureiro;
use App\Models\Missao;
use App\Services\MissoesCompletadasService;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MissaoWebController extends Controller
{
    public function __construct(private MissoesCompletadasService $missoesCompletadas)
    {
        //XD
    }

    public function index(): View
    {
        return view('missoes.index',[
            'missoes' => Missao::with('monstro')->orderBy('missao_nome')->get(),
        ]);
    }

    public function show(Request $request, Missao $missao): View
    {
        $guildaId = $this->guildaId($request);
        $missao->load('monstro');

        $concluidas = DB::table('missoes_completadas as mc')
            ->join('aventureiro as a', 'a.aventureiro_id', '=', 'mc.id_aventureiro')
            ->where('mc.id_missao', $missao->missao_id)
            ->where('a.id_guilda', $guildaId)
            ->orderBy('a.aventureiro_nome')
            ->get(['a.aventureiro_id', 'a.aventureiro_nome', 'mc.ouro_recebido', 'mc.xp_recebido']);

        $disponiveis = Aventureiro::where('id_guilda', $guildaId)
            ->whereNotIn('aventureiro_id', $concluidas->pluck('aventureiro_id'))
            ->orderBy('aventureiro_nome')
            ->get();

        return view('missoes.show', compact('missao', 'concluidas', 'disponiveis'));
    }

    public function vincular(Request $request, Missao $missao): RedirectResponse
    {
        $guildaId = $this->guildaId($request);

        $dados = $request->validate(
            ['id_aventureiro' => ['required', 'integer']],
            ['id_aventureiro.required' => 'Selecione um aventureiro.']
        );

        $aventureiro = Aventureiro::findOrFail($dados['id_aventureiro']);
        abort_unless((int) $aventureiro->id_guilda === $guildaId, 404);

        if ($this->jaConcluiu($aventureiro, $missao)) {
            return back()->with('erro', 'Este aventureiro já concluiu esta missão.');
        }

        try {
            $this->missoesCompletadas->createMissaoCompleta([
                'id_aventureiro' => $aventureiro->aventureiro_id,
                'id_missao' => $missao->missao_id,
            ]);
        } catch (QueryException $e) {
            return back()->with('erro', 'Não foi possível registrar a conclusão da missão.');
        }

        return back()->with('sucesso', $aventureiro->aventureiro_nome . ' foi vinculado à missão.');
    }

    public function trocar(Request $request, Missao $missao): RedirectResponse
    {
        $guildaId = $this->guildaId($request);

        $dados = $request->validate(
            [
                'antigo' => ['required', 'integer'],
                'novo' => ['required', 'integer', 'different:antigo'],
            ],
            [
                'novo.required' => 'Selecione o novo aventureiro.',
                'novo.different' => 'Escolha um aventureiro diferente do atual.',
            ]
        );

        $antigo = Aventureiro::findOrFail($dados['antigo']);
        $novo = Aventureiro::findOrFail($dados['novo']);

        abort_unless(
            (int) $antigo->id_guilda === $guildaId && (int) $novo->id_guilda === $guildaId,
            404
        );

        if ($this->jaConcluiu($novo, $missao)) {
            return back()->with('erro', 'O novo aventureiro já concluiu esta missão.');
        }

        try {
            $this->missoesCompletadas->upadateMissaoAventureirotaById(
                $missao->missao_id,
                $antigo->aventureiro_id,
                ['id_aventureiro' => $novo->aventureiro_id]
            );
        } catch (QueryException $e) {
            return back()->with('erro', 'Não foi possível trocar o aventureiro desta missão.');
        }

        return back()->with('sucesso', 'Missão passada de ' . $antigo->aventureiro_nome . ' para ' . $novo->aventureiro_nome . '.');
    }

    public function desvincular(Request $request, Missao $missao, Aventureiro $aventureiro): RedirectResponse
    {
        abort_unless((int) $aventureiro->id_guilda === $this->guildaId($request), 404);

        $this->missoesCompletadas->deleteMissaoAventureiroById(
            $missao->missao_id,
            $aventureiro->aventureiro_id
        );

        return back()->with('sucesso', 'Vínculo removido.');
    }

    private function jaConcluiu(Aventureiro $aventureiro, Missao $missao): bool
    {
        return DB::table('missoes_completadas')
            ->where('id_aventureiro', $aventureiro->aventureiro_id)
            ->where('id_missao', $missao->missao_id)
            ->exists();
    }

}