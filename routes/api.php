<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClasseController;

Route::apiResource('classe', ClasseController::class);