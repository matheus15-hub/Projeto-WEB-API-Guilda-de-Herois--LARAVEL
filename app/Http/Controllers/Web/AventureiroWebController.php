<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\AventureiroWebRequest;
use App\Models\Aventureiro;
use App\Models\Classe;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AventureiroWebController extends Controller
{
    public function index(Request $request): View
    {
        $aventureiros = Aventureiro::with('classe')
            ->where('id_guilda', $this->guildaId($request))
            ->when($request->filled('busca'), fn ($q) => $q->where(
                'aventureiro_nome', 'ilike', '%' . $request->input('busca') . '%'
            ))
            ->orderBy('aventureiro_nome')
            ->paginate(25)
            ->withQueryString();

        $classes = Classe::orderBy('classe_nome')->get();

        return view('aventureiros.index', compact('aventureiros', 'classes'));
    }

    /**
     * Exibe o formulário de criação de aventureiro.
     */
    public function create(): View
    {
        $classes = Classe::orderBy('classe_nome')->get();

        return view('aventureiros.create', compact('classes'));
    }

    /**
     * Salva um novo aventureiro vinculado à guilda do usuário autenticado.
     */
    public function store(AventureiroWebRequest $request): RedirectResponse
    {
        $dados = $request->validated();

        Aventureiro::create([
            'aventureiro_nome' => $dados['nome'],
            'idade'            => $dados['idade'],
            'experiencia'      => $dados['experiencia'] ?? 0,
            'ouro'             => $dados['ouro'] ?? 0,
            'id_classe'        => $dados['id_classe'],
            'id_guilda'        => $this->guildaId($request), // Injeta a guilda automaticamente do usuário logado
        ]);

        return redirect()->route('aventureiros.index')
            ->with('sucesso', 'Aventureiro criado com sucesso!');
    }

    public function show(Request $request, Aventureiro $aventureiro): View
    {
        $this->daMinhaGuilda($request, $aventureiro);

        $aventureiro->load('classe');
        $missoes = $aventureiro->missoes_completadas()->with('missao')->get();

        return view('aventureiros.show', compact('aventureiro', 'missoes'));
    }

    public function edit(Request $request, Aventureiro $aventureiro): View
    {
        $this->daMinhaGuilda($request, $aventureiro);

        return view('aventureiros.edit', [
            'aventureiro' => $aventureiro,
            'classes'     => Classe::orderBy('classe_nome')->get(),
        ]);
    }

    public function update(AventureiroWebRequest $request, Aventureiro $aventureiro): RedirectResponse
    {
        $this->daMinhaGuilda($request, $aventureiro);

        $dados = $request->validated();

        $aventureiro->update([
            'aventureiro_nome' => $dados['nome'],
            'idade'            => $dados['idade'],
            'experiencia'      => $dados['experiencia'] ?? 0,
            'ouro'             => $dados['ouro'] ?? 0,
            'id_classe'        => $dados['id_classe'],
        ]);

        return redirect()->route('aventureiros.index')
            ->with('sucesso', 'Aventureiro atualizado com sucesso.');
    }

    public function destroy(Request $request, Aventureiro $aventureiro): RedirectResponse
    {
        $this->daMinhaGuilda($request, $aventureiro);

        try {
            $aventureiro->delete();
        } catch (QueryException $e) {
            return redirect()->route('aventureiros.index')
                ->with('erro', 'Não foi possível excluir: este aventureiro possui missões registradas.');
        }

        return redirect()->route('aventureiros.index')
            ->with('sucesso', 'Aventureiro excluído com sucesso.');
    }

    private function daMinhaGuilda(Request $request, Aventureiro $aventureiro): void
    {
        abort_unless((int) $aventureiro->id_guilda === $this->guildaId($request), 404);
    }
}