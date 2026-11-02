<?php

namespace App\Interfaces;

use App\Models\Missao;
use Illuminate\Database\Eloquent\Collection;

interface MissaoRepositoryInterface
{
    public function create(array $dados): Missao;

    public function findById(int $id): Missao;

    public function all(): Collection;

    public function update(int $id, array $dados): Missao;

    public function delete(int $id): void;

    public function cadastrarMissao(array $dados): void;
}
