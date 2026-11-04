<?php

namespace App\Interfaces;

interface GuildaRepositoryInterface
{
    public function all();

    public function findById($id);

    public function create(array $dados);

    public function update(int $id, array $dados);

    public function delete(int $id);

    public function calcularOuroGuilda(int $idGuilda): int;

    public function listarMissoesCompletadasGuilda(int $idGuilda);
}