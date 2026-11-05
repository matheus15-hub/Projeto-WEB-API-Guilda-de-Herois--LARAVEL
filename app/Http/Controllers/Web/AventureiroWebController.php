<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\AventureiroWebRequest;
use App\Services\AventureiroService;
use App\Services\ClasseService;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AventureiroWebController extends Controller
{
    public function __construct(
        private AventureiroService $aventureiroService,
        private ClasseService $classeService
    ) {}

    public function index(Request $request): View
    {
        $idGuilda = $this->guildaId($request);

        $aventureiros = $this->aventureiroService->listarParaWeb(
            $idGuilda,
            $request->input('busca')
        );

        $classes = $this->classeService->getClasseAll();

        return view('aventureiros.index', compact(
            'aventureiros',
            'classes'
        ));
    }

    public function create(Request $request): View
    {
        return $this->index($request);
    }

    public function store(AventureiroWebRequest $request): RedirectResponse
    {
        $dados = $request->validated();

        $dados['experiencia'] = $dados['experiencia'] ?? 0;
        $dados['ouro'] = $dados['ouro'] ?? 0;
        $dados['id_guilda'] = $this->guildaId($request);

        $this->aventureiroService->createAventureiro($dados);

        return redirect()->route('aventureiros.index')
            ->with('sucesso', 'Aventureiro criado com sucesso!');
    }

    public function show(Request $request, int $aventureiro): View
    {
        $aventureiro = $this->aventureiroService->buscarDetalhesParaWeb(
            $aventureiro,
            $this->guildaId($request)
        );

        $missoes = $aventureiro->missoes_completadas;

        return view('aventureiros.show', compact('aventureiro', 'missoes'));
    }

    public function edit(Request $request, int $aventureiro): View
    {
        $aventureiro = $this->aventureiroService->buscarPorIdEGuilda(
            $aventureiro,
            $this->guildaId($request)
        );

        $classes = $this->classeService->getClasseAll();

        return view('aventureiros.edit', compact(
            'aventureiro',
            'classes'
        ));
    }

    public function update(
        AventureiroWebRequest $request,
        int $aventureiro
    ): RedirectResponse {
        $idGuilda = $this->guildaId($request);

        $this->aventureiroService->buscarPorIdEGuilda(
            $aventureiro,
            $idGuilda
        );

        $dados = $request->validated();

        $dados['experiencia'] = $dados['experiencia'] ?? 0;
        $dados['ouro'] = $dados['ouro'] ?? 0;

        $this->aventureiroService->updateAventureiroById(
            $aventureiro,
            $dados
        );

        return redirect()->route('aventureiros.index')
            ->with('sucesso', 'Aventureiro atualizado com sucesso.');
    }

    public function destroy(
        Request $request,
        int $aventureiro
    ): RedirectResponse {
        $idGuilda = $this->guildaId($request);

        $this->aventureiroService->buscarPorIdEGuilda(
            $aventureiro,
            $idGuilda
        );

        try {
            $this->aventureiroService->deleteAventureiroById($aventureiro);
        } catch (QueryException $e) {
            return redirect()->route('aventureiros.index')
                ->with(
                    'erro',
                    'Não foi possível excluir: este aventureiro possui missões registradas.'
                );
        }

        return redirect()->route('aventureiros.index')
            ->with('sucesso', 'Aventureiro excluído com sucesso.');
    }
}
