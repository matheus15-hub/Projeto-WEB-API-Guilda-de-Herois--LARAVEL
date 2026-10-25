<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    // Teste 1: Ele verifica se a página inicial abre
    public function test_pagina_inicial_carrega_corretamente(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('welcome');
    }

    // Teste 2: Ele verifica se a página apresenta informações
    public function test_pagina_inicial_apresenta_conteudo(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Laravel');
    }
}
