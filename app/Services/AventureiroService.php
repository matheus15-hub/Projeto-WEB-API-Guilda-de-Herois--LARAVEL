<?php

namespace App\Services;

use App\Interfaces\AventureiroRepositoryInterface;
use Illuminate\Support\Collection;
use stdClass;

class AventureiroService
{
    public function __construct(
        private AventureiroRepositoryInterface $repository,
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
        $this->existAventureiro($id);

        return $this->repository->update($id, $dados);
    }

    public function deleteAventureiroById(int $id): void
    {
        $this->existAventureiro($id);

        $this->repository->delete($id);
    }

    public function transferirAventureiro(int $idAventureiro,?int $novaGuildaId): void {
        $this->repository->transferirAventureiro(
            $idAventureiro,
            $novaGuildaId
        );
    }

    public function listarPorGuilda( int $idGuilda, ?string $busca = null  )
    {
        return $this->repository->listarPorGuilda(
            $idGuilda,
            $busca
        );
    }

    public function listarParaWeb( int $idGuilda,?string $busca = null) {
        return $this->repository->listarParaWeb(
            $idGuilda,
            $busca
        );
    }

    public function buscarPorIdEGuilda( int $id,int $idGuilda)
     {
        return $this->repository->buscarPorIdEGuilda(
            $id,
            $idGuilda
        );
    }

    public function buscarDetalhesParaWeb(int $id, int $idGuilda) 
    {
        return $this->repository->buscarDetalhesParaWeb(
            $id,
            $idGuilda
        );
    }

    public function existAventureiro(int $id): bool
    {
        return $this->repository->existe($id);
    }
}
