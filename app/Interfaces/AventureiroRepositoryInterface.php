<?php
namespace App\Interfaces;

interface AventureiroRepositoryInterface{

    public function all();

    public function findById(int $id);

    public function create(array $dados);

    public function upadate(int $id, array $dados);

    public function delete(int $id);

}