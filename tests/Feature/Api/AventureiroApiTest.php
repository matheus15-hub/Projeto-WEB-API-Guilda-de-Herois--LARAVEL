<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Mockery;
use App\Services\AventureiroService;
use Illuminate\Validation\PresenceVerifierInterface;

#php artisan test --filter=AventureiroApiTest
class AventureiroApiTest extends TestCase
{
    protected $aventureiroMock;

    protected function setUp(): void
    {
        parent::setUp();

        // Cria o mock do Service
        $this->aventureiroMock = Mockery::mock(AventureiroService::class);

        // Substitui o Service real pelo mock
        $this->app->instance(
            AventureiroService::class,
            $this->aventureiroMock
        );

        // Simula a validação das regras "exists"
        $presenceVerifier = Mockery::mock(PresenceVerifierInterface::class);

        $presenceVerifier->shouldReceive('getCount')->andReturn(1);
        $presenceVerifier->shouldReceive('getMultiCount')->andReturn(1);

        $this->app['validator']->setPresenceVerifier($presenceVerifier);
    }

    public function test_consultar_aventureiros(): void
    {
        $aventureiros = collect([
            (object) [
                'aventureiro_id' => 1,
                'aventureiro_nome' => 'Gandalf',
                'idade' => 60,
                'experiencia' => 100,
                'ouro' => 1,
                'id_classe' => 1,
                'id_guilda' => 1
            ]
        ]);

        $this->aventureiroMock
            ->shouldReceive('getAventureiroAll')
            ->once()
            ->andReturn($aventureiros);

        $response = $this->getJson('/api/aventureiro');

        $response->assertOk()
            ->assertJsonStructure([
                'message',
                'data'
            ]);
    }

    public function test_consultar_aventureiro_por_id(): void
    {
        $aventureiro = (object) [
            'aventureiro_id' => 1,
            'aventureiro_nome' => 'Gandalf',
            'idade' => 60,
            'experiencia' => 100,
            'ouro' => 1,
            'id_classe' => 1,
            'id_guilda' => 1
        ];

        $this->aventureiroMock
            ->shouldReceive('getAventureiroByID')
            ->with(1)
            ->once()
            ->andReturn($aventureiro);

        $response = $this->getJson('/api/aventureiro/1');

        $response->assertOk()
            ->assertJsonStructure([
                'message',
                'data'
            ]);
    }

        public function test_criar_aventureiro(): void
        {
            $dados = [
                'nome' => 'Gandalf',
                'idade' => 60,
                'experiencia' => 100,
                'id_classe' => 1,
                'id_guilda' => 1
            ];

            $aventureiro = (object) [
                'aventureiro_id' => 10,
                'aventureiro_nome' => 'Gandalf',
                'idade' => 60,
                'experiencia' => 100,
                'ouro' => 1,
                'id_classe' => 1,
                'id_guilda' => 1
            ];

            $this->aventureiroMock
                ->shouldReceive('createAventureiro')
                ->with($dados)
                ->once()
                ->andReturn($aventureiro);

            $response = $this->postJson('/api/aventureiro', $dados);

            $response->assertCreated()
                ->assertJsonStructure([
                    'message',
                    'data'
                ]);
        }

        public function test_criando_aventureiro_sem_guilda()
        {
            $dados = [
                'nome' => 'Artur Roman',
                'idade' => 15,
                'experiencia' => 0,
                'id_classe' => 1
            ];
            $aventureiro = (object) [
                'aventureiro_id' => 1,
                'nome' => 'Artur Roman',
                'idade' => 15,
                'experiencia' => 0,
                'id_classe' => 1
            ];

            $this->aventureiroMock
            ->shouldReceive('createAventureiro')
            ->with($dados)
            ->once()
            ->andReturn($aventureiro);

            $response = $this->postJson('api/aventureiro', $dados);

            $response->assertCreated()
            ->assertJsonStructure([
                'message',
                'data'
            ]);
        }

        public function test_campos_obrigatorios_criacao()
        {
            $dados = [];
            $response = $this->postJson('api/aventureiro', $dados);

            $response->assertUnprocessable()
            ->assertJsonValidationErrors(
                [
                    'nome',
                    'idade',
                    'experiencia',
                    'id_classe',
                ]
            );
        }

    public function test_atualizar_aventureiro(): void
    {
        $aventureiroAntigo = (object) [
            'aventureiro_id' => 1,
            'aventureiro_nome' => 'Gandalf',
            'idade' => 60,
            'experiencia' => 100,
            'ouro' => 1,
            'id_classe' => 1,
            'id_guilda' => 1
        ];

        $dados = [
            'nome' => 'Gandalf Atualizado',
            'idade' => 61,
            'id_classe' => 1,
            'id_guilda' => 1
        ];

        $aventureiroAtualizado = (object) [
            'aventureiro_id' => 1,
            'aventureiro_nome' => 'Gandalf Atualizado',
            'idade' => 61,
            'id_classe' => 1,
            'id_guilda' => 1
        ];

        $this->aventureiroMock
            ->shouldReceive('getAventureiroByID')
            ->with(1)
            ->once()
            ->andReturn($aventureiroAntigo);

        $this->aventureiroMock
            ->shouldReceive('updateAventureiroById')
            ->with(1, $dados)
            ->once()
            ->andReturn($aventureiroAtualizado);

        $response = $this->putJson('/api/aventureiro/1', $dados);

        $response->assertOk()
            ->assertJsonStructure([
                'message',
                'data' => [
                    'antes',
                    'depois'
                ]
            ]);
    }

    public function test_excluir_aventureiro(): void
    {
        $aventureiro = (object) [
            'aventureiro_id' => 1,
            'aventureiro_nome' => 'Gandalf',
            'idade' => 60,
            'experiencia' => 100,
            'ouro' => 1,
            'id_classe' => 1,
            'id_guilda' => 1
        ];

        $this->aventureiroMock
            ->shouldReceive('getAventureiroByID')
            ->with(1)
            ->once()
            ->andReturn($aventureiro);

        $this->aventureiroMock
            ->shouldReceive('deleteAventureiroById')
            ->with(1)
            ->once()
            ->andReturnNull();

        $response = $this->deleteJson('/api/aventureiro/1');

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
