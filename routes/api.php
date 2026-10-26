<?php

use App\Http\Controllers\Api\AventureiroController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClasseController;
use App\Http\Controllers\Api\GuildaController;
use App\Http\Controllers\Api\MonstroController;

Route::apiResource('classe', ClasseController::class);

Route::apiResource('guilda',GuildaController::class);

Route::apiResource('aventureiro', AventureiroController::class);

Route::apiResource('monstro', MonstroController::class);