<?php

namespace Tests;

use App\Models\Classe;
use Tests\WebTestCase;

class ClasseWebTest extends WebTestCase
{
    public function test_visitante_e_redirecionado_ao_login(): void
    {
        $this->get('/classes')->assertRedirect(route('login'));
        $this->get('/classes/create')->assertRedirect(route('login'));
    }

    public function test_index_lista_as_classes(): void
    {
        $this->logarComoSecretario();
        $this->criarClasse('Guerreiro');
        $this->criarClasse('Mago', 'A');

        $response = $this->get('/classes');

        $response->assertOk();
        $response->assertViewIs('classes.index');
        $response->assertSee('Guerreiro');
        $response->assertSee('Mago');
    }

    public function test_pagina_de_criacao_abre_com_as_raridades(): void
    {
        $this->logarComoSecretario();

        $response = $this->get('/classes/create');

        $response->assertOk();
        $response->assertViewIs('classes.create');
        $response->assertViewHas('raridades');
    }

    public function test_store_sem_dados_retorna_erros_de_validacao(): void
    {
        $this->logarComoSecretario();

        $response = $this->post('/classes', []);

        $response->assertSessionHasErrors(['nome', 'descricao', 'raridade']);
        $this->assertSame(0, Classe::count());
    }

    public function test_store_com_raridade_invalida_e_rejeitado(): void
    {
        $this->logarComoSecretario();

        $response = $this->post('/classes', [
            'nome' => 'Arqueiro',
            'descricao' => 'Ataca de longe',
            'raridade' => 'Z',
        ]);

        $response->assertSessionHasErrors(['raridade']);
    }

    public function test_store_valido_cria_a_classe(): void
    {
        $this->logarComoSecretario();

        $response = $this->post('/classes', [
            'nome' => 'Mago Arcano',
            'descricao' => 'Usa magia',
            'raridade' => 'S++',
        ]);

        $response->assertRedirect(route('classes.index'));
        $response->assertSessionHas('sucesso', 'Classe cadastrada com sucesso.');
        $this->assertDatabaseHas('classe', ['classe_nome' => 'Mago Arcano', 'raridade' => 'S++']);
    }

    public function test_edit_mostra_a_classe_e_update_altera(): void
    {
        $this->logarComoSecretario();
        $classe = $this->criarClasse('Guerreiro');

        $this->get(route('classes.edit', $classe))
            ->assertOk()
            ->assertViewIs('classes.edit')
            ->assertSee('Guerreiro');

        $response = $this->put(route('classes.update', $classe), [
            'nome' => 'Paladino',
            'descricao' => 'Guerreiro sagrado',
            'raridade' => 'B',
        ]);

        $response->assertRedirect(route('classes.index'));
        $this->assertDatabaseHas('classe', ['classe_id' => $classe->classe_id, 'classe_nome' => 'Paladino']);
    }

    public function test_destroy_remove_a_classe(): void
    {
        $this->logarComoSecretario();
        $classe = $this->criarClasse('Ladino');

        $response = $this->delete(route('classes.destroy', $classe));

        $response->assertRedirect(route('classes.index'));
        $response->assertSessionHas('sucesso', 'Classe excluída com sucesso.');
        $this->assertDatabaseMissing('classe', ['classe_id' => $classe->classe_id]);
    }
}