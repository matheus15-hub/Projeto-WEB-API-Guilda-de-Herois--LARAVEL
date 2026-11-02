<?php

namespace App\Interfaces;

use Illuminate\Support\Collection;
use stdClass;

interface AventureiroInterface
{
    public function createAventureiro(array $dados): stdClass;

    public function getAventureiroByID(int $id): stdClass;

    public function getAventureiroAll(): Collection;

    public function updateAventureiroById(int $id, array $dados): stdClass;

    public function deleteAventureiroById(int $id): void;

    public function calcularOuroGuilda(int $idGuilda): int;

    public function listarMissoesCompletadasGuilda(int $idGuilda);

    public function transferirAventureiro(int $idAventureiro, ?int $novaGuildaId): void;
}