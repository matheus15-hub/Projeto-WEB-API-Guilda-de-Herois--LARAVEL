<?php


namespace App\Http\Controllers;

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
        $aventureiros = Aventureiro::with('classes')
            ->where('id_guilda', $this->guildaId($request))
            ->orderBy('aventureiro_nome')
            ->get();

            return view('aventureiros.index', compact('aventureiros'));
    }
}
