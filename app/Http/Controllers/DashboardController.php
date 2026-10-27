<?php

namespace App\Http\Controllers;

use App\Models\Aventureiro;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): view
    {
        $user = $request->user()->load('guilda');

        //apenas vai ter os aventureiros e a guilda do usuário
        $aventureiros = $user->id_guilda
            ? Aventureiro::with('classe')
            ->where('id_guilda', $user->id_guilda)
            ->orderBy('aventureiro_nome')
            ->get()
            : collect();

        return view('dashboard', [
            'user' => $user,
            'guilda' => $user->guilda,
            'aventureiros' => $aventureiros,
        ]);
    }
}
