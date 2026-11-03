<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\AventureiroService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private AventureiroService $aventureiroService
    ) {}

    public function index(Request $request): View
    {
        $user = $request->user()->load('guilda');

        $aventureiros = $user->id_guilda
            ? $this->aventureiroService->listarPorGuilda(
                $user->id_guilda,
                $request->input('busca')
            )
            : collect();

        return view('dashboard', [
            'user' => $user,
            'guilda' => $user->guilda,
            'aventureiros' => $aventureiros,
        ]);
    }
}