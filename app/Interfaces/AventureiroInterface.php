<?php

namespace App\Interfaces;

use App\Models\Aventureiro;
use Illuminate\Database\Eloquent\Collection;

interface AventureiroInterface
{
    public function createAventureiro(array $dados): Aventureiro;

    public function getAventureiroByID(int $id): Aventureiro;

    public function getAventureiroAll(): Collection;

    public function updateAventureiroById(
        int $id,
        array $dados
    ): Aventureiro;

    public function deleteAventureiroById(int $id): void;

    public function calcularOuroGuilda(int $idGuilda): int;

    public function listarMissoesCompletadasGuilda(int $idGuilda);

    public function transferirAventureiro(int $idAventureiro,?int $novaGuildaId): void;
}