<?php
namespace App\Interfaces;

interface GuildaRepositoryInterface
{
    public function all();

    public function findById($id);

    public function create(array $dados);

    public function update(int $id, array $dados);

    public function delete(int $id);
}