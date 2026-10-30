<?php

namespace App\Services;

use App\Interfaces\GuildaRepositoryInterface;
use App\Models\Guilda;
use Illuminate\Support\Collection;

class GuildaService{
    
    public function __construct(private GuildaRepositoryInterface $repository){ }
    public function createGuilda(array $dados)  
    {
        return $this->repository->create($dados);
    }

    public function getGuildaById(int $id) 
    {
        return Guilda::findOrFail($id);
    }

    public function getGuildaAll() : Collection
    {
        return $this->repository->all();
    }

    public function updateGuildaById(int $id , array $dados) 
    {
        return $this->repository->update($id, $dados);
    }

    public function deleteGuildaById(int $id)
    {
        $this->getGuildaById($id);
        $this->repository->delete($id);
    }
}