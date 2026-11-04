<?php

namespace App\Services;

use App\Interfaces\MonstroRepositoryInterface;
use Illuminate\Support\Collection;
use stdClass;

class MonstroService
{
    public function __construct(
        private MonstroRepositoryInterface $repository
    ) {}

    public function createMonstro(array $dados): stdClass
    {
        return $this->repository->create($dados);
    }

    public function getMonstroById(int $id): stdClass
    {
        return $this->repository->findById($id);
    }

    public function getMonstroAll(): Collection
    {
        return $this->repository->all();
    }

    public function updateMonstroById(int $id, array $dados): stdClass
    {
        return $this->repository->update($id, $dados);
    }

    public function deleteMonstroById(int $id): void
    {
        $this->repository->delete($id);
    }
}
