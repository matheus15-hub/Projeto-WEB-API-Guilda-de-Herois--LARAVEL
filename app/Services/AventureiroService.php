<?php

namespace App\Services;


use App\Interfaces\AventureiroRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AventureiroService
{
    public function __construct(
        private AventureiroRepositoryInterface $repository,
        private GuildaService $guildaService
    ) {}

    public function createAventureiro(array $dados)
    {
        return $this->repository->create($dados);
    }

    public function getAventureiroByID(int $id)
    {
        return $this->repository->findById($id);
    }

    public function getAventureiroAll(): Collection
    {
        return $this->repository->all();
    }

    public function updateAventureiroById(int $id, array $dados)
    {
        return $this->repository->upadate($id, $dados);
    }

    public function deleteAventureiroById(int $id): void
    {
        $this->repository->delete($id);
    }

    public function calcularOuroGuilda(int $idGuilda): int
    {
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
            [$idAventureiro, $novaGuildaId]
        );
    }
}
