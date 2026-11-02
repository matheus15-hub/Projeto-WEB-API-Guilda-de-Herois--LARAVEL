<?php

namespace App\Interfaces;

interface ClasseRepositoryInterface
{
    public function findAll();

    public function findById(int $id);

    public function create(array $dados);

    public function update(int $id, array $dados);

    public function delete(int $id);
}