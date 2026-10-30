<?php

namespace App\Services;

use App\Enums\Raridade;
use App\Interfaces\ClasseRepositoryInterface;
use App\Models\Classe;
use Doctrine\DBAL\Query\From;
use Illuminate\Support\Collection;

class ClasseService{
    
    public function __construct(private ClasseRepositoryInterface $repository){}

    public function createClasse(array $dados)
    {
        return $this->repository->create($dados);
    }


    public function getClasseById(int $id) 
    {
        return $this->repository->findById($id);
    }

    public function getClasseAll() : Collection
    {
        return $this->repository->findAll();
    }

  
    public function updateClasseById(int $id, array $dados)
    {
      return $this->repository->update($id, $dados);
    }


    public function deleteClasseById(int $id) 
    {
        return $this->repository->delete($id);
    }
}
