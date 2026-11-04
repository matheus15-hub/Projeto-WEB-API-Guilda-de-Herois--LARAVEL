<?php

namespace App\Repositories;

use App\Interfaces\MissaoRepositoryInterface;
use App\Models\Missao;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class MissaoRepository implements MissaoRepositoryInterface
{
    public function create(array $dados): Missao
    {
        $missao = new Missao();

        $missao->missao_nome = $dados['nome'];
        $missao->recompensa_ouro = $dados['recompensa'];
        $missao->id_monstro = $dados['id_monstro'];
        $missao->quantidade_monstro = $dados['quantidade_monstros'];
        $missao->dificuldade = $dados['dificuldade'];

        $missao->save();

        return $missao->refresh();
    }

    public function findById(int $id): Missao
    {
        return Missao::findOrFail($id);
    }

    public function all(): Collection
    {
        return Missao::all();
    }

    public function update(int $id, array $dados): Missao
    {
        $missao = $this->findById($id);

        if (isset($dados['nome'])) {
            $missao->missao_nome = $dados['nome'];
        }

        if (isset($dados['recompensa'])) {
            $missao->recompensa_ouro = $dados['recompensa'];
        }

        if (isset($dados['id_monstro'])) {
            $missao->id_monstro = $dados['id_monstro'];
        }

        if (isset($dados['quantidade_monstros'])) {
            $missao->quantidade_monstro = $dados['quantidade_monstros'];
        }

        if (isset($dados['dificuldade'])) {
            $missao->dificuldade = $dados['dificuldade'];
        }

        $missao->save();

        return $missao->refresh();
    }

    public function delete(int $id): void
    {
        $missao = $this->findById($id);

        $missao->delete();
    }

    public function cadastrarMissao(array $dados): void
    {
        DB::statement(
            'CALL pr_cadastrar_missao(?, ?, ?, ?, ?)',
            [
                $dados['nome'],
                $dados['dificuldade'],
                $dados['recompensa'],
                $dados['id_monstro'],
                $dados['quantidade_monstros']
            ]
        );
    }
}
