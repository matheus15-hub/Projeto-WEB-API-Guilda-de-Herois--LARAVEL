<?php

namespace App\Services;

use App\Interfaces\AventureiroInterface;
use App\Interfaces\AventureiroRepositoryInterface;
use App\Repositories\AventureiroRepository;
use Illuminate\Support\Collection;
use stdClass;

class AventureiroService implements AventureiroInterface
{
    public function __construct(
        private AventureiroRepositoryInterface $repository,
        private GuildaService $guildaService
    ) {}

    public function createAventureiro(array $dados): stdClass
    {
        return $this->repository->create($dados);
    }

    public function getAventureiroByID(int $id): stdClass
    {
        return $this->repository->findById($id);
    }

    public function getAventureiroAll(): Collection
    {
        return $this->repository->all();
    }

    public function updateAventureiroById(int $id, array $dados): stdClass
    {
        return $this->repository->update($id, $dados);
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

    public function transferirAventureiro(int $idAventureiro, ?int $novaGuildaId): void
    {
        $this->repository->transferirAventureiro($idAventureiro, $novaGuildaId);
    }

    public function listarPorGuilda(int $idGuilda, ?string $busca = null)
    {
        return $this->repository->listarPorGuilda($idGuilda, $busca);
    }
}