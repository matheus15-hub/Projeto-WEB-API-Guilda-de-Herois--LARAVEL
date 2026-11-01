<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Aventureiro;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user()->load('guilda');

        $aventureiros = $user->id_guilda
            ? Aventureiro::with('classe')
                ->where('id_guilda', $user->id_guilda)
                ->when($request->filled('busca'), function ($q) use ($request) {
                    // escapa % e _ para a busca não virar coringa
                    $termo = addcslashes($request->input('busca'), '%_\\');
                    $q->where('aventureiro_nome', 'ilike', "%{$termo}%");
                })
                ->orderBy('aventureiro_nome')
                ->paginate(15)
                ->withQueryString()
            : Aventureiro::whereRaw('1 = 0')->paginate(15);

        return view('dashboard', [
            'user' => $user,
            'guilda' => $user->guilda,
            'aventureiros' => $aventureiros,
        ]);
    }
}