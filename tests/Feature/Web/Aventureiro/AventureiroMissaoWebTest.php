<?php

namespace Tests\Feature\Web;

use App\Services\MissoesCompletadasService;
use Mockery\MockInterface;
use Tests\WebTestCase;

class AventureiroMissaoWebTest extends WebTestCase
{
    public function test_visitante_e_redirecionado_ao_login(): void
    {
        $this->get('/aventureiros/1/missoes')->assertRedirect(route('login'));
    }

    public function test_index_lista_missoes_disponiveis_do_aventureiro(): void
    {
        $guilda = $this->criarGuilda();
        $this->logarComoSecretario($guilda);
        $aventureiro = $this->criarAventureiro($guilda->guilda_id, 'Aragorn');
        $this->criarMissao('Caçar Goblins', $this->criarMonstro('Goblin'));

        $response = $this->get(route('aventureiros.missoes.index', $aventureiro));

        $response->assertOk();
        $response->assertViewIs('aventureiros.missoes');
        $response->assertSee('Aragorn');
        $response->assertSee('Goblin');
    }

    public function test_aventureiro_de_outra_guilda_retorna_404(): void
    {
        $minha = $this->criarGuilda('Minha Guilda');
        $outra = $this->criarGuilda('Outra Guilda');
        $this->logarComoSecretario($minha);
        $alheio = $this->criarAventureiro($outra->guilda_id, 'Legolas');

        $this->get(route('aventureiros.missoes.index', $alheio))->assertNotFound();
    }

    public function test_store_sem_selecionar_missao_retorna_erro(): void
    {
        $guilda = $this->criarGuilda();
        $this->logarComoSecretario($guilda);
        $aventureiro = $this->criarAventureiro($guilda->guilda_id);

        $response = $this->post(route('aventureiros.missoes.store', $aventureiro), []);

        $response->assertSessionHasErrors(['missoes']);
    }

    public function test_store_vincula_a_missao_usando_o_service_mockado(): void
    {
        $guilda = $this->criarGuilda();
        $this->logarComoSecretario($guilda);
        $aventureiro = $this->criarAventureiro($guilda->guilda_id);
        $missao = $this->criarMissao();

        $this->mock(MissoesCompletadasService::class, function (MockInterface $mock) use ($aventureiro, $missao) {
            $mock->shouldReceive('createMissaoCompleta')
                ->once()
                ->with([
                    'id_aventureiro' => $aventureiro->aventureiro_id,
                    'id_missao' => $missao->missao_id,
                ]);
        });

        $response = $this->post(route('aventureiros.missoes.store', $aventureiro), [
            'missoes' => [$missao->missao_id],
        ]);

        $response->assertRedirect(route('aventureiros.missoes.index', $aventureiro));
        $response->assertSessionHas('sucesso', '1 missão vinculada.');
    }

    public function test_destroy_remove_o_vinculo_usando_o_service_mockado(): void
    {
        $guilda = $this->criarGuilda();
        $this->logarComoSecretario($guilda);
        $aventureiro = $this->criarAventureiro($guilda->guilda_id);
        $missao = $this->criarMissao();

        $this->mock(MissoesCompletadasService::class, function (MockInterface $mock) use ($aventureiro, $missao) {
            $mock->shouldReceive('deleteMissaoAventureiroById')
                ->once()
                ->with($missao->missao_id, $aventureiro->aventureiro_id);
        });

        $response = $this->delete(route('aventureiros.missoes.destroy', [$aventureiro, $missao]));

        $response->assertRedirect();
        $response->assertSessionHas('sucesso', 'Vínculo removido.');
    }
}