<?php

namespace App\Services;

use App\Interfaces\MonstroRepositoryInterface;
use App\Models\Monstro;
use Illuminate\Database\Eloquent\Collection;

class MonstroService
{
    public function __construct(
        private MonstroRepositoryInterface $repository
    ) {}

    public function createMonstro(array $dados): Monstro
    {
        return $this->repository->create($dados);
    }

    public function getMonstroById(int $id): Monstro
    {
        return $this->repository->findById($id);
    }

    public function getMonstroAll(): Collection
    {
        return $this->repository->all();
    }

    public function updateMonstroById(int $id, array $dados): Monstro
    {
        return $this->repository->update($id, $dados);
    }

    public function deleteMonstroById(int $id): void
    {
        $this->repository->delete($id);
    }
}
