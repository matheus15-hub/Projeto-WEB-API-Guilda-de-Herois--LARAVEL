<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Mockery;
use stdClass;
use App\Interfaces\AventureiroInterface;
use Illuminate\Validation\PresenceVerifierInterface;

# php artisan test --filter=AventureiroApiTest
class AventureiroApiTest extends TestCase
{
    protected $aventureiroMock;

    protected function setUp(): void
    {
        parent::setUp();

        // Cria o mock da interface
        $this->aventureiroMock = Mockery::mock(AventureiroInterface::class);

        // Substitui a implementação real pela simulada
        $this->app->instance(
            AventureiroInterface::class,
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
            'aventureiro_id' => 1,
            'aventureiro_nome' => 'Gandalf',
            'idade' => 60,
            'experiencia' => 100,
            'ouro' => 1,
            'id_classe' => 1,
            'id_guilda' => 1
        ];

        $this->aventureiroMock
            ->shouldReceive('createAventureiro')
            ->once()
            ->andReturn($aventureiro);

        $response = $this->postJson('/api/aventureiro', $dados);

        $response->assertCreated()
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
