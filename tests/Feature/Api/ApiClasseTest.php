<?php

namespace Tests\Feature\Api;

use App\Services\ClasseService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\PresenceVerifierInterface;
use Mockery;

use Tests\TestCase;

#php artisan test --filter=ApiClasseTest
class ApiClasseTest extends TestCase
{
    protected $classeMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->classeMock = Mockery::mock(ClasseService::class);

        $this->app->instance(
            ClasseService::class,
            $this->classeMock
        );

        $presenceVerifier = Mockery::mock(PresenceVerifierInterface::class);

        $presenceVerifier->shouldReceive('getCount')->andReturn(1);
        $presenceVerifier->shouldReceive('getMultiCount')->andReturn(1);

        $this->app['validator']->setPresenceVerifier($presenceVerifier);
    }

    public function test_retorno_registros() 
    {
        $classe = collect([
            (object)[
                'classe_id' => 1,
                'classe_nome' => 'MATATA',
                'raridade' => 'B'
            ],
            (object) [
                'classe_id' => 2,
                'classe_nome' => 'Mago',
                'raridade' => 'S'
            ]
        ]);

        $this->classeMock
        ->shouldReceive('getClasseAll')
        ->once()
        ->andReturn($classe);

        $responce =$this->getJson('api/classe');

        $responce->assertOk()
        ->assertJsonStructure([
            'message',
            'data'
        ]);
    }

    public function test_crindo_classe() : void
    {
        $dado = [
            'nome' => 'Cozinheiro',
            'descricao' => 'Alimentador de gente',
            'raridade' => 'E'
        ];

        $this->classeMock
        ->shouldReceive('createClasse')
        ->once()
        ->andReturn(10);

        $responce = $this->postJson('api/classe', $dado);

        $responce->assertCreated()
        ->assertJsonStructure([
            'message',
            'data'
        ]);
    }
    
    public function test_esperando_erro_nome()
    {
        $dados = [
        'descricao' => 'Alimentador de gente',
        'raridade' => 'E'
        ];

        $response = $this->postJson('/api/classe', $dados);
        //assertUnprocessable verefica se o retorno é 422 do http
        //assertJsonValidationErrors verefica se houve um erro em tal campo
        $response->assertUnprocessable() 
            ->assertJsonValidationErrors(['nome']);
    }

    public function test_retornando_classe_por_id()
    {
        $classe = (object) [
            'classe_id' => 2,
            'nome' => 'Cozinheiro',
            'raridade' => 'E'
        ];

        $this->classeMock
        ->shouldReceive('getClasseById')
        ->with(2)
        ->once()
        ->andReturn($classe);

        $responce = $this->getJson('api/classe/2');

        $responce->assertOk()
        ->assertJsonStructure([
            'message',
            'data'
        ]);
    }
}