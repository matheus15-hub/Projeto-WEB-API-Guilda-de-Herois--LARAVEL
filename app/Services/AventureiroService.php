<?php

namespace App\Services;

use App\Interfaces\AventureiroInterface;
use App\Models\Aventureiro;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AventureiroService implements AventureiroInterface{

    public function __construct(private GuildaService $guildaService) {


    }

    public function createAventureiro(array $dados): Aventureiro{
        $aventureiro = new Aventureiro();

        $aventureiro->aventureiro_nome = $dados['nome'];
        $aventureiro->idade = $dados['idade'];
        $aventureiro->experiencia = $dados['experiencia'];
        $aventureiro->ouro = $dados['ouro'];
        $aventureiro->id_classe = $dados['id_classe'];
        $aventureiro->id_guilda = $dados['id_guilda'];

        $aventureiro->save();

        return $aventureiro->refresh();
    }

    public function getAventureiroByID(int $id): Aventureiro{
        return Aventureiro::findOrFail($id);
    }

    public function getAventureiroAll(): Collection{
        return Aventureiro::all();
    }

    public function updateAventureiroById(
        int $id,
        array $dados
    ): Aventureiro {

        $aventureiro = $this->getAventureiroByID($id);

        if (isset($dados['nome'])){
            $aventureiro->aventureiro_nome = $dados['nome'];
        }

        if (isset($dados['idade'])){
            $aventureiro->idade = $dados['idade'];
        }

        if (isset($dados['id_classe'])){
            $aventureiro->id_classe = $dados['id_classe'];
        }

        if (isset($dados['id_guilda'])){
            $aventureiro->id_guilda = $dados['id_guilda'];
        }

        $aventureiro->save();

        return $aventureiro->refresh();
    }

    public function deleteAventureiroById(int $id): void{
        $aventureiro = $this->getAventureiroByID($id);

        $aventureiro->delete();
    }

    public function calcularOuroGuilda(int $idGuilda): int {
        return $this->guildaService->calcularOuroGuilda($idGuilda);
    }

    public function listarMissoesCompletadasGuilda(int $idGuilda)
    {
        return $this->guildaService->listarMissoesCompletadasGuilda($idGuilda);
    }
    public function transferirAventureiro(
        int $idAventureiro,
        ?int $novaGuildaId
    ): void {
        DB::statement(
            'CALL pr_transferir_aventureiro(?, ?)',
            [
                $idAventureiro,
                $novaGuildaId
            ]
        );
    }
}