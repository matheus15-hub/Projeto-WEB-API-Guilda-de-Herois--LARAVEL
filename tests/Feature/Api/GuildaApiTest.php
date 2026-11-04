<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Mockery;
use App\Services\GuildaService;
use Illuminate\Validation\PresenceVerifierInterface;

#php artisan test --filter=GuildaApiTest
class GuildaApiTest extends TestCase
{
    protected $guildaMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->guildaMock = Mockery::mock(GuildaService::class);

        $this->app->instance(
            GuildaService::class,
            $this->guildaMock
        );

        $presenceVerifier = Mockery::mock(PresenceVerifierInterface::class);

        $presenceVerifier->shouldReceive('getCount')->andReturn(1);
        $presenceVerifier->shouldReceive('getMultiCount')->andReturn(1);

        $this->app['validator']->setPresenceVerifier($presenceVerifier);
    }

    public function test_consultar_guildas(): void
    {
        $guildas = collect([
            (object) [
                'guilda_id' => 1,
                'guilda_nome' => 'Ordem dos Heróis'
            ],
            (object) [
                'guilda_id' => 2,
                'guilda_nome' => 'Cavaleiros'
            ]
        ]);

        $this->guildaMock
            ->shouldReceive('getGuildaAll')
            ->once()
            ->andReturn($guildas);

        $response = $this->getJson('/api/guilda');

        $response->assertOk()
            ->assertJsonStructure([
                'message',
                'data'
            ]);
    }

    public function test_consultar_guilda_por_id(): void
    {
        $guilda = (object) [
            'guilda_id' => 1,
            'guilda_nome' => 'Ordem dos Heróis'
        ];

        $this->guildaMock
            ->shouldReceive('getGuildaById')
            ->with(1)
            ->once()
            ->andReturn($guilda);

        $response = $this->getJson('/api/guilda/1');

        $response->assertOk()
            ->assertJsonStructure([
                'message',
                'data'
            ]);
    }

    public function test_criar_guilda(): void
    {
        $dados = [
            'nome' => 'Ordem dos Heróis'
        ];

        $guilda = (object) [
            'guilda_id' => 1,
            'guilda_nome' => 'Ordem dos Heróis'
        ];

        $this->guildaMock
            ->shouldReceive('createGuilda')
            ->once()
            ->andReturn($guilda);

        $response = $this->postJson('/api/guilda', $dados);

        $response->assertCreated()
            ->assertJsonStructure([
                'message',
                'data'
            ]);
    }

    public function test_campos_obrigatorios_guilda()
    {
        $dados = [];

        $response = $this->postJson('api/guilda',$dados);

        $response->assertUnprocessable()
        ->assertJsonValidationErrors(
            'nome'
        );
    }

    public function test_atualizar_guilda(): void
    {
        $guildaAntiga = (object) [
            'guilda_id' => 1,
            'guilda_nome' => 'Ordem Antiga'
        ];

        $guildaAtualizada = (object) [
            'guilda_id' => 1,
            'guilda_nome' => 'Ordem dos Heróis'
        ];

        $this->guildaMock
            ->shouldReceive('getGuildaById')
            ->with(1)
            ->once()
            ->andReturn($guildaAntiga);

        $this->guildaMock
            ->shouldReceive('updateGuildaById')
            ->with(1, Mockery::type('array'))
            ->once()
            ->andReturn($guildaAtualizada);

        $response = $this->putJson('/api/guilda/1', [
            'nome' => 'Ordem dos Heróis'
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

    public function test_deletar_guilda(): void
    {
        $guilda = (object) [
            'guilda_id' => 1,
            'guilda_nome' => 'Ordem dos Heróis'
        ];

        $this->guildaMock
            ->shouldReceive('getGuildaById')
            ->with(1)
            ->once()
            ->andReturn($guilda);

        $this->guildaMock
            ->shouldReceive('deleteGuildaById')
            ->with(1)
            ->once()
            ->andReturnNull();

        $response = $this->deleteJson('/api/guilda/1');

        $response->assertOk()
            ->assertJsonStructure([
                'message',
                'data'
            ]);
    }

    public function test_consultar_ouro_da_guilda(): void
    {
        $this->guildaMock
            ->shouldReceive('calcularOuroGuilda')
            ->with(1)
            ->once()
            ->andReturn(500);

        $response = $this->getJson('/api/guilda/1/ouro');

        $response->assertOk()
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id_guilda',
                    'ouro_total'
                ]
            ]);
    }

    public function test_consultar_missoes_completadas_da_guilda(): void
    {
        $missoes = collect([
            (object) [
                'missao_id' => 1,
                'missao_nome' => 'Derrotar o Dragão'
            ]
        ]);

        $this->guildaMock
            ->shouldReceive('listarMissoesCompletadasGuilda')
            ->with(1)
            ->once()
            ->andReturn($missoes);

        $response = $this->getJson('/api/guilda/1/missoes-completadas');

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