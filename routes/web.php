<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\AventureiroMissaoController;
use App\Http\Controllers\Web\AventureiroWebController;
use App\Http\Controllers\Web\ClasseWebController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\MissaoWebController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

// Login e registro (não conectados)
Route::middleware('guest')->group(function () {
    Route::get('/cadastro', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/cadastro', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
});

// Área autenticada
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Aventureiros (Agora inclui a rota /aventureiros/create)
    Route::resource('aventureiros', AventureiroWebController::class)
        ->parameters(['aventureiros' => 'aventureiro']);

    // Missões de cada aventureiro
    Route::get('/aventureiros/{aventureiro}/missoes', [AventureiroMissaoController::class, 'index'])
        ->name('aventureiros.missoes.index');
    Route::post('/aventureiros/{aventureiro}/missoes', [AventureiroMissaoController::class, 'store'])
        ->name('aventureiros.missoes.store');
    Route::delete('/aventureiros/{aventureiro}/missoes/{missao}', [AventureiroMissaoController::class, 'destroy'])
        ->name('aventureiros.missoes.destroy');

    // Classes
    Route::resource('classes', ClasseWebController::class)
        ->except('show')
        ->parameters(['classes' => 'classe']);

    // Missões vistas pelo lado da missão
    Route::get('/missoes', [MissaoWebController::class, 'index'])->name('missoes.index');
    Route::get('/missoes/{missao}', [MissaoWebController::class, 'show'])->name('missoes.show');
    Route::post('/missoes/{missao}/aventureiros', [MissaoWebController::class, 'vincular'])->name('missoes.vincular');
    Route::post('/missoes/{missao}/trocar', [MissaoWebController::class, 'trocar'])->name('missoes.trocar');
    Route::delete('/missoes/{missao}/aventureiros/{aventureiro}', [MissaoWebController::class, 'desvincular'])->name('missoes.desvincular');
});