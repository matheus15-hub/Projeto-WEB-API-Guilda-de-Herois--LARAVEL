<?php

namespace Tests\Feature\Web;

use App\Models\User;
use Tests\WebTestCase;

class AventureiroWebTest extends WebTestCase
{
    public function test_visitante_e_redirecionado_ao_login(): void
    {
        $this->get('/aventureiros')->assertRedirect(route('login'));
    }

    public function test_usuario_sem_guilda_recebe_403(): void
    {
        $this->actingAs(User::create(['name' => 'sem_guilda', 'password' => 'senha12345']));

        $this->get('/aventureiros')->assertForbidden();
    }

    public function test_index_mostra_so_os_aventureiros_da_propria_guilda(): void
    {
        $minha = $this->criarGuilda('Minha Guilda');
        $outra = $this->criarGuilda('Outra Guilda');
        $this->logarComoSecretario($minha);

        $this->criarAventureiro($minha->guilda_id, 'Aragorn');
        $this->criarAventureiro($outra->guilda_id, 'Legolas');

        $response = $this->get('/aventureiros');

        $response->assertOk();
        $response->assertViewIs('aventureiros.index');
        $response->assertSee('Aragorn');
        $response->assertDontSee('Legolas');
    }

    public function test_show_abre_aventureiro_da_guilda_e_bloqueia_o_de_outra(): void
    {
        $minha = $this->criarGuilda('Minha Guilda');
        $outra = $this->criarGuilda('Outra Guilda');
        $this->logarComoSecretario($minha);

        $meu = $this->criarAventureiro($minha->guilda_id, 'Aragorn');
        $alheio = $this->criarAventureiro($outra->guilda_id, 'Legolas');

        $this->get(route('aventureiros.show', $meu))
            ->assertOk()
            ->assertViewIs('aventureiros.show')
            ->assertSee('Aragorn');

        $this->get(route('aventureiros.show', $alheio))->assertNotFound();
    }

    public function test_edit_abre_o_formulario_preenchido(): void
    {
        $guilda = $this->criarGuilda();
        $this->logarComoSecretario($guilda);
        $classe = $this->criarClasse('Guerreiro');
        $aventureiro = $this->criarAventureiro($guilda->guilda_id, 'Aragorn', $classe->classe_id);

        $this->get(route('aventureiros.edit', $aventureiro))
            ->assertOk()
            ->assertViewIs('aventureiros.edit')
            ->assertSee('Aragorn')
            ->assertSee('Guerreiro');
    }

    public function test_update_valido_altera_o_aventureiro(): void
    {
        $guilda = $this->criarGuilda();
        $this->logarComoSecretario($guilda);
        $classe = $this->criarClasse();
        $aventureiro = $this->criarAventureiro($guilda->guilda_id, 'Aragorn', $classe->classe_id);

        $response = $this->put(route('aventureiros.update', $aventureiro), [
            'nome' => 'Aragorn Rei',
            'idade' => 87,
            'experiencia' => 10,
            'ouro' => 5,
            'id_classe' => $classe->classe_id,
        ]);

        $response->assertRedirect(route('aventureiros.index'));
        $this->assertDatabaseHas('aventureiro', [
            'aventureiro_id' => $aventureiro->aventureiro_id,
            'aventureiro_nome' => 'Aragorn Rei',
            'idade' => 87,
        ]);
    }

    public function test_update_sem_dados_retorna_erros_de_validacao(): void
    {
        $guilda = $this->criarGuilda();
        $this->logarComoSecretario($guilda);
        $aventureiro = $this->criarAventureiro($guilda->guilda_id, 'Aragorn');

        $response = $this->put(route('aventureiros.update', $aventureiro), []);

        $response->assertSessionHasErrors(['nome', 'idade', 'id_classe']);
    }

    public function test_destroy_remove_o_aventureiro(): void
    {
        $guilda = $this->criarGuilda();
        $this->logarComoSecretario($guilda);
        $aventureiro = $this->criarAventureiro($guilda->guilda_id, 'Aragorn');

        $response = $this->delete(route('aventureiros.destroy', $aventureiro));

        $response->assertRedirect(route('aventureiros.index'));
        $this->assertDatabaseMissing('aventureiro', ['aventureiro_id' => $aventureiro->aventureiro_id]);
    }
}