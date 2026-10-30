<?php

namespace App\Services;

use App\Interfaces\AventureiroRepositoryInterface;
use App\Models\Aventureiro;
use Illuminate\Support\Collection;

class AventureiroService{

    public function __construct(private AventureiroRepositoryInterface $repository){}

    public function createAventureiro(array $dados) 
    {
        return $this->repository->create($dados);
    }

    public function getAventureiroByID(int $id)
    {
        return  $this->repository->findById($id);
    }
    public function getAventureiroAll() : Collection 
    {
        return $this->repository->all();
    }
    public function updateAventureiroById(int $id , array $dados)
    {   
        return $this->repository->upadate($id,$dados);
    }

    public function deleteAventureiroById(int $id) : void
    {
         $this->repository->delete($id);
    }

}