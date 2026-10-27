<?php

namespace Tests\Feature\Api;

use App\Services\ClasseService;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class ApiClasseTest extends TestCase
{
    public function test_consulta_classe(): void
    {
        // Utilizando o Metodo Mock para realizar os testes sem afetar o banco
        $this->mock(ClasseService::class, function ($mock)
        {
            $mock
            ->shouldReceive('getClasseAll')
            ->once()
            ->andReturn(new Collection());
        });

        $response = $this->getJson('/api/classe');

        $response->assertOk();
}
}