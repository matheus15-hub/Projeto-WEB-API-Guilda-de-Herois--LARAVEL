<?php

namespace Tests\Feature\Web;

use Tests\TestCase;

class RotasPublicasTest extends TestCase
{
    public function test_pagina_inicial(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertViewIs('welcome');
    }

    public function test_pagina_login(): void
    {
        $response = $this->get('/login');

        $response->assertOk();
        $response->assertViewIs('auth.login');
    }

    public function test_login_sem_credenciais(): void
    {
        $response = $this->post('/login', []);

        $response->assertSessionHasErrors(['name', 'password']);
    }

    public function test_cadastro_sem_dados(): void
    {
        $response = $this->post('/cadastro', []);

        $response->assertSessionHasErrors();
    }
}
