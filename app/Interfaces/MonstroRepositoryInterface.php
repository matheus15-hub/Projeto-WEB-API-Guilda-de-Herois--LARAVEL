<?php

namespace App\Interfaces;

use App\Models\Monstro;
use Illuminate\Support\Collection;
use stdClass;

interface MonstroRepositoryInterface
{
    public function create(array $dados): stdClass;

    public function findById(int $id): stdClass;

    public function all(): Collection;

    public function update(int $id, array $dados): stdClass;

    public function delete(int $id): void;
}
