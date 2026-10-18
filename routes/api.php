<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClasseController;
use App\Http\Controllers\Api\GuildaController;

Route::apiResource('classe', ClasseController::class);

Route::apiResource('guilda',GuildaController::class);