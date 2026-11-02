<?php

namespace Tests\Feature\Web;

use Tests\WebTestCase;

class CadastroWebTest extends WebTestCase
{
    public function test_pagina_de_cadastro_lista_as_guildas(): void
    {
        $this->criarGuilda('Guilda do Norte');

        $response = $this->get('/cadastro');

        $response->assertOk();
        $response->assertViewIs('auth.registro');
        $response->assertSee('Guilda do Norte');
    }

    public function test_cadastro_valido_cria_usuario_e_redireciona_ao_dashboard(): void
    {
        $guilda = $this->criarGuilda();

        $response = $this->post('/cadastro', [
            'name' => 'novo_secretario',
            'id_guilda' => $guilda->guilda_id,
            'password' => 'senha12345',
            'password_confirmation' => 'senha12345',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('users', [
            'name' => 'novo_secretario',
            'id_guilda' => $guilda->guilda_id,
        ]);
        $this->assertAuthenticated();
    }

    public function test_cadastro_rejeita_guilda_inexistente_e_senha_sem_confirmacao(): void
    {
        $response = $this->post('/cadastro', [
            'name' => 'fulano',
            'id_guilda' => 999,
            'password' => 'senha12345',
            'password_confirmation' => 'outra_senha',
        ]);

        $response->assertSessionHasErrors(['id_guilda', 'password']);
        $this->assertDatabaseMissing('users', ['name' => 'fulano']);
    }

    public function test_usuario_logado_nao_acessa_o_cadastro(): void
    {
        $this->logarComoSecretario();

        $this->get('/cadastro')->assertRedirect();
    }
}