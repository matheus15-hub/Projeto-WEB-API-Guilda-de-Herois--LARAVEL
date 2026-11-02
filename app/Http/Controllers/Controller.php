<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class Controller
{
    protected function guildaId(Request $request): int
    {
        $id = $request->user()->id_guilda;
        abort_if($id === null, 403, 'Seu usuário não está vinculado a nenhuma guilda.');

        return (int) $id;
    }
}