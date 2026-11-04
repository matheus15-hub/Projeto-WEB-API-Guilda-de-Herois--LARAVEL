<?php

namespace App\Interfaces;

use Illuminate\Support\Collection;

interface MissoesCompletadasRepositoryInterface
{
    public function aventureiroExiste(int $idAventureiro): bool;

    public function idsJaConcluidos(int $idAventureiro, array $idsMissao): array;

    public function buscarDadosRecompensaVarias(array $idsMissao): Collection;

    public function inserirVarias(array $linhas): void;

    public function listarConcluidasPorAventureiro(int $idAventureiro);

    public function listarDisponiveisPorAventureiro(int $idAventureiro);

    public function aventureiroJaConcluiu(int $idAventureiro, int $idMissao): bool;
}