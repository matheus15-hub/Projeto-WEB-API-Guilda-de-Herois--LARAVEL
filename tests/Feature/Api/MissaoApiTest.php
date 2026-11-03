<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Mockery;
use App\Services\MissaoService;
use App\Models\Missao;
use Illuminate\Validation\PresenceVerifierInterface;
use Illuminate\Database\Eloquent\Collection;

# php artisan test --filter=MissaoApiTest
class MissaoApiTest extends TestCase
{
    protected $missaoMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->missaoMock = Mockery::mock(MissaoService::class);

        $this->app->instance(
            MissaoService::class,
            $this->missaoMock
        );

        $presenceVerifier = Mockery::mock(PresenceVerifierInterface::class);

        $presenceVerifier->shouldReceive('getCount')->andReturn(1);
        $presenceVerifier->shouldReceive('getMultiCount')->andReturn(1);

        $this->app['validator']->setPresenceVerifier($presenceVerifier);
    }

    public function test_consultar_missoes(): void
    {
        $missoes = new Collection([
            (object) [
                'missao_id' => 1,
                'missao_nome' => 'Derrotar o Dragão',
                'recompensa_ouro' => 500
            ],
            (object) [
                'missao_id' => 2,
                'missao_nome' => 'Proteger a Vila',
                'recompensa_ouro' => 200
            ]
        ]);

        $this->missaoMock
            ->shouldReceive('getMissaoAll')
            ->once()
            ->andReturn($missoes);

        $response = $this->getJson('/api/missao');

        $response->assertOk()
            ->assertJsonStructure([
                'message',
                'data'
            ]);
    }

    public function test_consultar_missao_por_id(): void
    {
        $missao = new Missao([
            'missao_id' => 1,
            'missao_nome' => 'Derrotar o Dragão',
            'recompensa_ouro' => 500
        ]);

        $this->missaoMock
            ->shouldReceive('getMissaoById')
            ->with(1)
            ->once()
            ->andReturn($missao);

        $response = $this->getJson('/api/missao/1');

        $response->assertOk()
            ->assertJsonStructure([
                'message',
                'data'
            ]);
    }

    public function test_criar_missao(): void
    {
        $dados = [
            'nome' => 'Derrotar o Dragão',
            'recompensa' => 500,
            'id_monstro' => 1,
            'quantidade_monstros' => 5,
            'dificuldade' => 'A'
        ];

        $missao = new Missao([
            'missao_id' => 1,
            'missao_nome' => 'Derrotar o Dragão',
            'recompensa_ouro' => 500
        ]);

        $this->missaoMock
            ->shouldReceive('createMissao')
            ->once()
            ->andReturn($missao);

        $response = $this->postJson('/api/missao', $dados);

        $response->assertCreated()
            ->assertJsonStructure([
                'message',
                'data'
            ]);
    }

    public function test_atualizar_missao(): void
    {
        $missaoAntiga = new Missao([
            'missao_id' => 1,
            'missao_nome' => 'Missão Antiga',
            'recompensa_ouro' => 200
        ]);

        $missaoAtualizada = new Missao([
            'missao_id' => 1,
            'missao_nome' => 'Derrotar o Dragão',
            'recompensa_ouro' => 500
        ]);

        $this->missaoMock
            ->shouldReceive('getMissaoById')
            ->with(1)
            ->once()
            ->andReturn($missaoAntiga);

        $this->missaoMock
            ->shouldReceive('updateMissaoById')
            ->with(1, Mockery::type('array'))
            ->once()
            ->andReturn($missaoAtualizada);

        $response = $this->putJson('/api/missao/1', [
            'nome' => 'Derrotar o Dragão',
            'recompensa' => 500,
            'id_monstro' => 1
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'message',
                'data' => [
                    'antes',
                    'depois'
                ]
            ]);
    }

    public function test_deletar_missao(): void
    {
        $missao = new Missao([
            'missao_id' => 1,
            'missao_nome' => 'Derrotar o Dragão',
            'recompensa_ouro' => 500
        ]);

        $this->missaoMock
            ->shouldReceive('getMissaoById')
            ->with(1)
            ->once()
            ->andReturn($missao);

        $this->missaoMock
            ->shouldReceive('deleteMissaoById')
            ->with(1)
            ->once()
            ->andReturnNull();

        $response = $this->deleteJson('/api/missao/1');

        $response->assertOk()
            ->assertJsonStructure([
                'message',
                'data'
            ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}