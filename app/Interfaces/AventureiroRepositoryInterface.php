<?php

namespace App\Interfaces;

interface AventureiroRepositoryInterface
{
    public function all();

    public function findById(int $id);

    public function create(array $dados);

    public function update(int $id, array $dados);

    public function delete(int $id): void;

    public function transferirAventureiro(int $idAventureiro, ?int $novaGuildaId): void;

    public function listarPorGuilda(int $idGuilda, ?string $busca = null);

    public function existe(int $id): bool;

    public function buscarPorIdEGuilda(int $id, int $idGuilda);

    public function listarParaWeb( int $idGuilda,?string $busca = null);

    public function buscarDetalhesParaWeb(int $id,int $idGuilda);
}