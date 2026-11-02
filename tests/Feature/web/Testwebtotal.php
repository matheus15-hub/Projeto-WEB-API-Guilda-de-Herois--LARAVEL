<?php

namespace Tests;

use App\Models\Aventureiro;
use App\Models\Classe;
use App\Models\Guilda;
use App\Models\Missao;
use App\Models\Monstro;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

abstract class WebTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (config('database.default') !== 'sqlite') {
            $this->markTestSkipped('Estes testes só rodam com SQLite em memória (veja phpunit.xml).');
        }

        $this->criarTabelas();
    }

    private function criarTabelas(): void
    {
        Schema::create('users', function (Blueprint $t) {
            $t->id();
            $t->string('name')->unique();
            $t->string('password');
            $t->rememberToken();
            $t->timestamps();
            $t->unsignedBigInteger('id_guilda')->nullable();
        });

        Schema::create('guilda', function (Blueprint $t) {
            $t->increments('guilda_id');
            $t->string('guilda_nome');
            $t->string('lema')->nullable();
        });

        Schema::create('classe', function (Blueprint $t) {
            $t->increments('classe_id');
            $t->string('classe_nome');
            $t->text('descricao')->nullable();
            $t->string('raridade')->nullable();
        });

        Schema::create('monstro', function (Blueprint $t) {
            $t->increments('monstro_id');
            $t->string('monstro_nome');
            $t->integer('drop_xp')->default(0);
        });

        Schema::create('missao', function (Blueprint $t) {
            $t->increments('missao_id');
            $t->string('missao_nome');
            $t->string('dificuldade')->nullable();
            $t->integer('recompensa_ouro')->default(0);
            $t->unsignedBigInteger('id_monstro')->nullable();
            $t->integer('quantidade_monstro')->default(1);
        });

        Schema::create('aventureiro', function (Blueprint $t) {
            $t->increments('aventureiro_id');
            $t->string('aventureiro_nome');
            $t->integer('idade')->nullable();
            $t->integer('experiencia')->default(0);
            $t->unsignedBigInteger('id_classe')->nullable();
            $t->unsignedBigInteger('id_guilda')->nullable();
            $t->integer('ouro')->default(0);
        });

        Schema::create('missoes_completadas', function (Blueprint $t) {
            $t->increments('id');
            $t->unsignedBigInteger('id_aventureiro');
            $t->unsignedBigInteger('id_missao');
            $t->integer('ouro_recebido')->default(0);
            $t->integer('xp_recebido')->default(0);
        });
    }

    protected function criarGuilda(string $nome = 'Guilda Teste'): Guilda
    {
        return Guilda::create(['guilda_nome' => $nome, 'lema' => 'Lema de teste']);
    }

    protected function logarComoSecretario(?Guilda $guilda = null, string $nome = 'secretario'): User
    {
        $guilda ??= $this->criarGuilda();

        $user = User::create([
            'name' => $nome,
            'password' => 'senha12345',
            'id_guilda' => $guilda->guilda_id,
        ]);

        $this->actingAs($user);

        return $user;
    }

    protected function criarClasse(string $nome = 'Guerreiro', string $raridade = 'C'): Classe
    {
        return Classe::create([
            'classe_nome' => $nome,
            'descricao' => 'Descrição de teste',
            'raridade' => $raridade,
        ]);
    }

    protected function criarAventureiro(int $idGuilda, string $nome = 'Aragorn', ?int $idClasse = null): Aventureiro
    {
        return Aventureiro::create([
            'aventureiro_nome' => $nome,
            'idade' => 30,
            'experiencia' => 0,
            'ouro' => 0,
            'id_classe' => $idClasse,
            'id_guilda' => $idGuilda,
        ]);
    }

    protected function criarMissao(string $nome = 'Caçar Goblins', ?Monstro $monstro = null): Missao
    {
        return Missao::create([
            'missao_nome' => $nome,
            'dificuldade' => 'F',
            'recompensa_ouro' => 100,
            'id_monstro' => $monstro?->monstro_id,
            'quantidade_monstro' => 3,
        ]);
    }

    protected function criarMonstro(string $nome = 'Goblin'): Monstro
    {
        return Monstro::create(['monstro_nome' => $nome, 'drop_xp' => 10]);
    }
}