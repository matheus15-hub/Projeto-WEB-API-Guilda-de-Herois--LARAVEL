<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Mockery;
use App\Services\MonstroService;
use Illuminate\Support\Collection;
use Illuminate\Validation\PresenceVerifierInterface;

// php artisan test --filter=MonstroApiTest
class MonstroApiTest extends TestCase
{
    private $monstroMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->monstroMock = Mockery::mock(MonstroService::class);

        $this->app->instance(
            MonstroService::class,
            $this->monstroMock
        );

        $presenceVerifier = Mockery::mock(
            PresenceVerifierInterface::class
        );

        $presenceVerifier->shouldReceive('getCount')->andReturn(1);
        $presenceVerifier->shouldReceive('getMultiCount')->andReturn(1);

        $this->app['validator']->setPresenceVerifier($presenceVerifier);
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function test_consultar_monstros(): void
    {
        $monstro1 = (object) [
            'monstro_id' => 1,
            'monstro_nome' => 'Dragão',
            'drop_xp' => 100
        ];

        $monstro2 = (object) [
            'monstro_id' => 2,
            'monstro_nome' => 'Goblin',
            'drop_xp' => 50
        ];

        $retorno = new Collection([
            $monstro1,
            $monstro2
        ]);

        $this->monstroMock
            ->shouldReceive('getMonstroAll')
            ->once()
            ->andReturn($retorno);

        $response = $this->getJson('/api/monstro');

        $response->assertOk()
            ->assertJson([
                'message' => 'Monstros encontrados com sucesso!',
                'data' => [
                    [
                        'monstro_id' => 1,
                        'monstro_nome' => 'Dragão',
                        'drop_xp' => 100
                    ],
                    [
                        'monstro_id' => 2,
                        'monstro_nome' => 'Goblin',
                        'drop_xp' => 50
                    ]
                ]
            ]);
    }

    public function test_criar_monstro(): void
    {
        $dados = [
            'nome' => 'Dragão',
            'drop_xp' => 100
        ];

        $retorno = (object) [
            'monstro_id' => 1,
            'monstro_nome' => 'Dragão',
            'drop_xp' => 100
        ];

        $this->monstroMock
            ->shouldReceive('createMonstro')
            ->once()
            ->andReturn($retorno);

        $response = $this->postJson('/api/monstro', $dados);

        $response->assertCreated()
            ->assertJson([
                'message' => 'Monstro criado com sucesso!',
                'data' => [
                    'monstro_id' => 1,
                    'monstro_nome' => 'Dragão',
                    'drop_xp' => 100
                ]
            ]);
    }

    public function test_campos_obrigatorios_monstro(): void
    {
        $response = $this->postJson('/api/monstro', []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors([
                'nome',
                'drop_xp'
            ]);
    }

    public function test_consultar_monstro_por_id(): void
    {
        $retorno = (object) [
            'monstro_id' => 1,
            'monstro_nome' => 'Dragão',
            'drop_xp' => 100
        ];

        $this->monstroMock
            ->shouldReceive('getMonstroById')
            ->with(1)
            ->once()
            ->andReturn($retorno);

        $response = $this->getJson('/api/monstro/1');

        $response->assertOk()
            ->assertJson([
                'message' => 'Monstro encontrado com sucesso!',
                'data' => [
                    'monstro_id' => 1,
                    'monstro_nome' => 'Dragão',
                    'drop_xp' => 100
                ]
            ]);
    }

    public function test_atualizar_monstro(): void
    {
        $dadosAtualizacao = [
            'drop_xp' => 200
        ];

        $antes = (object) [
            'monstro_id' => 1,
            'monstro_nome' => 'Dragão',
            'drop_xp' => 100
        ];

        $depois = (object) [
            'monstro_id' => 1,
            'monstro_nome' => 'Dragão',
            'drop_xp' => 200
        ];

        $this->monstroMock
            ->shouldReceive('getMonstroById')
            ->with(1)
            ->once()
            ->andReturn($antes);

        $this->monstroMock
            ->shouldReceive('updateMonstroById')
            ->with(1, $dadosAtualizacao)
            ->once()
            ->andReturn($depois);

        $response = $this->putJson('/api/monstro/1', [
            'drop_xp' => 200
        ]);

        $response->assertOk()
            ->assertJson([
                'message' => 'Monstro atualizado com sucesso!',
                'data' => [
                    'antes' => [
                        'monstro_id' => 1,
                        'monstro_nome' => 'Dragão',
                        'drop_xp' => 100
                    ],
                    'depois' => [
                        'monstro_id' => 1,
                        'monstro_nome' => 'Dragão',
                        'drop_xp' => 200
                    ]
                ]
            ]);
    }

    public function test_deletar_monstro(): void
    {
        $retorno = (object) [
            'monstro_id' => 1,
            'monstro_nome' => 'Dragão',
            'drop_xp' => 100
        ];

        $this->monstroMock
            ->shouldReceive('getMonstroById')
            ->with(1)
            ->once()
            ->andReturn($retorno);

        $this->monstroMock
            ->shouldReceive('deleteMonstroById')
            ->with(1)
            ->once()
            ->andReturnNull();

        $response = $this->deleteJson('/api/monstro/1');

        $response->assertOk()
            ->assertJson([
                'message' => 'Monstro deletado com sucesso!',
                'data' => [
                    'monstro_id' => 1,
                    'monstro_nome' => 'Dragão',
                    'drop_xp' => 100
                ]
            ]);
    }
}
