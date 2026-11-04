<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AventureiroController;
use App\Http\Controllers\Api\ClasseController;
use App\Http\Controllers\Api\GuildaController;
use App\Http\Controllers\Api\MissaoController;
use App\Http\Controllers\Api\MissoesCompletadasController;
use App\Http\Controllers\Api\MonstroController;


Route::apiResource('classe', ClasseController::class);

Route::apiResource('guilda', GuildaController::class);

Route::get('guilda/{idGuilda}/ouro', [GuildaController::class, 'ouro']);

Route::get('guilda/{idGuilda}/missoes-completadas', [GuildaController::class, 'missoesCompletadas']);


Route::apiResource('aventureiro', AventureiroController::class);

Route::patch('aventureiro/{id}/guilda', [AventureiroController::class, 'transferir']);


Route::apiResource('monstro', MonstroController::class);


Route::apiResource('missao', MissaoController::class);


Route::apiResource('missoes_completas', MissoesCompletadasController::class);

Route::get('missoes_completas/{idAve}/{idMissao}', [MissoesCompletadasController::class, 'show']);

Route::put('missoes_completas/{idAve}/{idMissao}', [MissoesCompletadasController::class, 'update']);

Route::delete('missoes_completas/{idAventureiro}/{idMissao}', [MissoesCompletadasController::class, 'destroy']);

Route::get('aventureiro/{id}/missoes/concluidas', [MissoesCompletadasController::class, 'concluidasPorAventureiro']);

Route::get('aventureiro/{id}/missoes/disponiveis', [MissoesCompletadasController::class, 'disponiveisPorAventureiro']);

Route::post('aventureiro/{id}/missoes/completadas/lote', [MissoesCompletadasController::class, 'storeVarias']);
