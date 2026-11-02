<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Enums\Raridade;
use App\Http\Requests\ClasseWebRequest;
use App\Models\Classe;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ClasseWebController extends Controller
{
    public function index(): View
    {
            return view('classes.index', [
                'classes' => Classe::withCount('aventureiros')->orderBy('classe_nome')->get(),
            ]);
    }
    public function create(): View 
    {
            return view('classes.create', ['raridades' => Raridade::cases()]);
    }

    public function store(ClasseWebRequest $request): RedirectResponse
    {
            $dados = $request->validated();

            Classe::create([
                'classe_nome' => $dados['nome'],
                'descricao' => $dados['descricao'],
                'raridade' => $dados['raridade'],
            ]);

            return redirect()->route('classes.index')
            ->with('sucesso', 'Classe cadastrada com sucesso.');
    }

    public function edit(Classe $classe): View
    {
            return view('classes.edit', [
                'classe' => $classe,
                'raridades' => Raridade::cases(),
            ]);
    }

    public function update(ClasseWebRequest $request, Classe $classe): RedirectResponse
    {
            $dados = $request->validated();

            $classe->update([
                'classe_nome' => $dados['nome'],
                'descricao' => $dados['descricao'],
                'raridade' => $dados['raridade'],
            ]);
            return redirect()->route('classes.index')
                ->with('sucesso', 'Classe atualizada com sucesso.');
    }

    public function destroy(Classe $classe): RedirectResponse
    {
        try {
            $classe->delete();
            } catch (QueryException $e) {
                return redirect()->route('classes.index')
                    ->with('erro', 'Não foi possível excluir: existem aventureiros usando esta classe.');
            }
        return redirect()->route('classes.index')
            ->with('sucesso', 'Classe excluída com sucesso.');
    }
    }

