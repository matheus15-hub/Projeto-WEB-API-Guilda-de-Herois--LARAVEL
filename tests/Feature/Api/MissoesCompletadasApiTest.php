<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Mockery;
use App\Services\MissoesCompletadasService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\PresenceVerifierInterface;

#php artisan test --filter=MissoesCompletadasApiTest
class MissoesCompletadasApiTest extends TestCase
{
    private $missoesCompletadasMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->missoesCompletadasMock = Mockery::mock(
            MissoesCompletadasService::class
        );

        $this->app->instance(
            MissoesCompletadasService::class,
            $this->missoesCompletadasMock
        );

        $presenceVerifier = Mockery::mock(
            PresenceVerifierInterface::class
        );

        $presenceVerifier->shouldReceive('getCount')
            ->andReturn(1);

        $presenceVerifier->shouldReceive('getMultiCount')
            ->andReturn(1);

        $this->app['validator']->setPresenceVerifier(
            $presenceVerifier
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function test_consultar_missoes_completadas(): void
    {
        $paginacao = new LengthAwarePaginator(
            collect([
                (object) [
                    'id_missao' => 1,
                    'nome_missao' => 'Derrotar o Dragão',
                    'monstro' => 'Dragão',
                    'quantidade' => 2,
                    'recompensa' => 500,
                    'xp' => 200,
                    'id_aventureiro' => 1,
                    'aventureiro' => 'João',
                    'classe' => 'Guerreiro'
                ]
            ]),
            1,
            10,
            1
        );

        $this->missoesCompletadasMock
            ->shouldReceive('getMissoesCompletasAll')
            ->with(10)
            ->once()
            ->andReturn($paginacao);

        $response = $this->getJson('/api/missoes_completas');

        $response->assertOk()
            ->assertJsonStructure([
                'message',
                'data' => [
                    'current_page',
                    'data' => [
                        '*' => [
                            'id_missao',
                            'nome_missao',
                            'monstro',
                            'quantidade',
                            'recompensa',
                            'xp',
                            'id_aventureiro',
                            'aventureiro',
                            'classe'
                        ]
                    ],
                    'per_page',
                    'total'
                ]
            ]);

        $response->assertJson([
            'message' => 'Missões completadas encontradas com sucesso!',
            'data' => [
                'data' => [
                    [
                        'id_missao' => 1,
                        'nome_missao' => 'Derrotar o Dragão',
                        'monstro' => 'Dragão',
                        'quantidade' => 2,
                        'recompensa' => 500,
                        'xp' => 200,
                        'id_aventureiro' => 1,
                        'aventureiro' => 'João',
                        'classe' => 'Guerreiro'
                    ]
                ]
            ]
        ]);
    }

    public function test_registrar_missao_completada(): void
    {
        $dados = [
            'id_aventureiro' => 1,
            'id_missao' => 1
        ];

        $retorno = [
            'id_aventureiro' => 1,
            'id_missao' => 1,
            'ouro_recebido' => 500,
            'xp_recebido' => 200
        ];

        $this->missoesCompletadasMock
            ->shouldReceive('createMissaoCompleta')
            ->with($dados)
            ->once()
            ->andReturn($retorno);

        $response = $this->postJson(
            '/api/missoes_completas',
            $dados
        );

        $response->assertCreated()
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id_aventureiro',
                    'id_missao',
                    'ouro_recebido',
                    'xp_recebido'
                ]
            ]);

        $response->assertJson([
            'message' => 'Missão completada registrada com sucesso!',
            'data' => $retorno
        ]);
    }

    public function test_consultar_missao_completada_por_id(): void
    {
        $retorno = collect([
            (object) [
                'id_missao' => 1,
                'nome_missao' => 'Derrotar o Dragão',
                'monstro' => 'Dragão',
                'quantidade' => 2,
                'recompensa' => 500,
                'xp' => 200,
                'id_aventureiro' => 1,
                'aventureiro' => 'João',
                'classe' => 'Guerreiro'
            ]
        ]);

        $this->missoesCompletadasMock
            ->shouldReceive('getMissaoAventureiroById')
            ->with(1, 1)
            ->once()
            ->andReturn($retorno);

        $response = $this->getJson(
            '/api/missoes_completas/1/1'
        );

        $response->assertOk()
            ->assertJsonStructure([
                'message',
                'data'
            ]);

        $response->assertJson([
            'message' => 'Missão completada encontrada com sucesso!',
            'data' => [
                [
                    'id_missao' => 1,
                    'nome_missao' => 'Derrotar o Dragão',
                    'monstro' => 'Dragão',
                    'quantidade' => 2,
                    'recompensa' => 500,
                    'xp' => 200,
                    'id_aventureiro' => 1,
                    'aventureiro' => 'João',
                    'classe' => 'Guerreiro'
                ]
            ]
        ]);
    }

    public function test_atualizar_missao_completada(): void
    {
        $dados = [
            'id_aventureiro' => 2
        ];

        $antes = collect([
            (object) [
                'id_missao' => 1,
                'id_aventureiro' => 1,
                'nome_missao' => 'Derrotar o Dragão'
            ]
        ]);

        $depois = (object) [
            'id_aventureiro' => 2,
            'id_missao' => 1
        ];

        $this->missoesCompletadasMock
            ->shouldReceive('getMissaoAventureiroById')
            ->with(1, 1)
            ->once()
            ->andReturn($antes);

        $this->missoesCompletadasMock
            ->shouldReceive('upadateMissaoAventureirotaById')
            ->with(1, 1, $dados)
            ->once()
            ->andReturn($depois);

        $response = $this->putJson(
            '/api/missoes_completas/1/1',
            $dados
        );

        $response->assertOk()
            ->assertJsonStructure([
                'message',
                'data' => [
                    'antes',
                    'depois'
                ]
            ]);

        $response->assertJson([
            'message' => 'Missão completada atualizada com sucesso!',
            'data' => [
                'antes' => [
                    [
                        'id_missao' => 1,
                        'id_aventureiro' => 1,
                        'nome_missao' => 'Derrotar o Dragão'
                    ]
                ],
                'depois' => [
                    'id_aventureiro' => 2,
                    'id_missao' => 1
                ]
            ]
        ]);
    }

    public function test_deletar_missao_completada(): void
    {
        $retorno = collect([
            (object) [
                'id_missao' => 1,
                'id_aventureiro' => 1,
                'nome_missao' => 'Derrotar o Dragão'
            ]
        ]);

        $this->missoesCompletadasMock
            ->shouldReceive('getMissaoAventureiroById')
            ->with(1, 1)
            ->once()
            ->andReturn($retorno);

        $this->missoesCompletadasMock
            ->shouldReceive('deleteMissaoAventureiroById')
            ->with(1, 1)
            ->once()
            ->andReturnNull();

        $response = $this->deleteJson(
            '/api/missoes_completas/1/1'
        );

        $response->assertOk()
            ->assertJsonStructure([
                'message',
                'data'
            ]);

        $response->assertJson([
            'message' => 'Missão completada deletada com sucesso!',
            'data' => [
                [
                    'id_missao' => 1,
                    'id_aventureiro' => 1,
                    'nome_missao' => 'Derrotar o Dragão'
                ]
            ]
        ]);
    }
}