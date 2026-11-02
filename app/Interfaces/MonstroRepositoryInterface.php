<?php

namespace App\Interfaces;

use App\Models\Monstro;
use Illuminate\Database\Eloquent\Collection;

interface MonstroRepositoryInterface
{
    public function create(array $dados): Monstro;

    public function findById(int $id): Monstro;

    public function all(): Collection;

    public function update(int $id, array $dados): Monstro;

    public function delete(int $id): void;
}
