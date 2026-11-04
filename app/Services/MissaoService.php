<?php

namespace App\Services;

use App\Interfaces\MissaoRepositoryInterface;
use App\Models\Missao;
use Illuminate\Database\Eloquent\Collection;

class MissaoService
{
    public function __construct(
        private MissaoRepositoryInterface $repository
    ) {}

    public function createMissao(array $dados): Missao
    {
        return $this->repository->create($dados);
    }

    public function getMissaoById(int $id): Missao
    {
        return $this->repository->findById($id);
    }

    public function getMissaoAll(): Collection
    {
        return $this->repository->all();
    }

    public function updateMissaoById(int $id, array $dados): Missao
    {
        return $this->repository->update($id, $dados);
    }

    public function deleteMissaoById(int $id): void
    {
        $this->repository->delete($id);
    }

    public function cadastrarMissao(array $dados): void
    {
        $this->repository->cadastrarMissao($dados);
    }
}
