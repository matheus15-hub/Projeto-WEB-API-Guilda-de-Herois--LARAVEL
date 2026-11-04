<?php

namespace App\Repositories;

use App\Interfaces\AventureiroRepositoryInterface;
use Illuminate\Support\Facades\DB;

class AventureiroRepository implements AventureiroRepositoryInterface
{
    public function all()
    {
        return DB::table('aventureiro')->get();
    }

    public function findById(int $id)
    {
        $aventureiro = DB::table('aventureiro')
            ->where('aventureiro_id', $id)
            ->first();

        if (!$aventureiro) {
            abort(404, 'Aventureiro não encontrado.');
        }

        return $aventureiro;
    }

    public function create(array $dados)
    {
        $id = DB::table('aventureiro')->insertGetId([
            'aventureiro_nome' => $dados['nome'],
            'idade' => $dados['idade'],
            'experiencia' => $dados['experiencia'] ?? 0,
            'ouro' => $dados['ouro'] ?? 1,
            'id_classe' => $dados['id_classe'],
            'id_guilda' => $dados['id_guilda'] ?? null,
        ], 'aventureiro_id');

        return $this->findById($id);
    }

    public function update(int $id, array $dados)
    {
        $campos = [
            'nome' => 'aventureiro_nome',
            'idade' => 'idade',
            'experiencia' => 'experiencia',
            'ouro' => 'ouro',
            'id_classe' => 'id_classe',
            'id_guilda' => 'id_guilda',
        ];

        $atualizacao = [];

        foreach ($campos as $entrada => $coluna) {
            if (array_key_exists($entrada, $dados)) {
                $atualizacao[$coluna] = $dados[$entrada];
            }
        }

        if (!empty($atualizacao)) {
            DB::table('aventureiro')
                ->where('aventureiro_id', $id)
                ->update($atualizacao);
        }

        return $this->findById($id);
    }

    public function delete(int $id): void
    {
        DB::table('aventureiro')
            ->where('aventureiro_id', $id)
            ->delete();
    }

    public function transferirAventureiro(int $idAventureiro, ?int $novaGuildaId): void
    {
        DB::statement('CALL pr_transferir_aventureiro(?, ?)', [
            $idAventureiro,
            $novaGuildaId
        ]);
    }

    public function listarPorGuilda(int $idGuilda, ?string $busca = null)
    {
        return DB::table('aventureiro as a')
            ->leftJoin('classe as c', 'c.classe_id', '=', 'a.id_classe')
            ->where('a.id_guilda', $idGuilda)
            ->when($busca, function ($query) use ($busca) {
                $query->where('a.aventureiro_nome', 'ilike', "%{$busca}%");
            })
            ->orderBy('a.aventureiro_nome')
            ->select('a.*', 'c.classe_nome')
            ->paginate(15)
            ->withQueryString();
    }

    public function existe(int $id): bool
    {
        return DB::table('aventureiro')
            ->where('aventureiro_id', $id)
            ->exists();
    }
}