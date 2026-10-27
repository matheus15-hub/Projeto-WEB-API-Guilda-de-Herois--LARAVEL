<?php

use App\Http\Controllers\Api\AventureiroController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClasseController;
use App\Http\Controllers\Api\GuildaController;
use App\Http\Controllers\Api\MissaoController;
use App\Http\Controllers\Api\MissoesCompletadasController;
use App\Http\Controllers\Api\MonstroController;

Route::apiResource('classe', ClasseController::class);

Route::apiResource('guilda',GuildaController::class);

Route::apiResource('aventureiro', AventureiroController::class);

Route::apiResource('monstro', MonstroController::class);

Route::apiResource('missao',MissaoController::class);

Route::apiResource('missoes_completas', MissoesCompletadasController::class);

Route::get( 'missoes_completas/{idAve}/{idMissao}', [MissoesCompletadasController::class, 'show']);
Route::put('missoes_completas/{idAve}/{idMissao}',   [MissoesCompletadasController::class, 'update']);
Route::delete('/missoes_completas/{idAventureiro}/{idMissao}',[MissoesCompletadasController::class, 'destroy']);