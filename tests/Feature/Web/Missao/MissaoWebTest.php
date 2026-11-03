<?php

namespace Tests\Feature\Web;

use App\Services\MissoesCompletadasService;
use Illuminate\Support\Facades\DB;
use Mockery\MockInterface;
use Tests\WebTestCase;

class MissaoWebTest extends WebTestCase
{
    public function test_visitante_e_redirecionado_ao_login(): void
    {
        $this->get('/missoes')->assertRedirect(route('login'));
        $this->get('/missoes/1')->assertRedirect(route('login'));
    }

    public function test_index_lista_as_missoes_com_o_monstro(): void
    {
        $this->logarComoSecretario();
        $this->criarMissao('Caçar Goblins', $this->criarMonstro('Goblin'));

        $response = $this->get('/missoes');

        $response->assertOk();
        $response->assertViewIs('missoes.index');
        $response->assertSee('Goblin');
    }

    public function test_show_mostra_quem_da_guilda_concluiu_a_missao(): void
    {
        $guilda = $this->criarGuilda();
        $this->logarComoSecretario($guilda);
        $aventureiro = $this->criarAventureiro($guilda->guilda_id, 'Aragorn');
        $missao = $this->criarMissao('Caçar Goblins');

        DB::table('missoes_completadas')->insert([
            'id_aventureiro' => $aventureiro->aventureiro_id,
            'id_missao' => $missao->missao_id,
            'ouro_recebido' => 100,
            'xp_recebido' => 30,
        ]);

        $response = $this->get(route('missoes.show', $missao));

        $response->assertOk();
        $response->assertViewIs('missoes.show');
        $response->assertSee('Aragorn');
    }

    public function test_show_sem_guilda_retorna_403(): void
    {
        $this->actingAs(\App\Models\User::create(['name' => 'sem_guilda', 'password' => 'senha12345']));
        $missao = $this->criarMissao();

        $this->get(route('missoes.show', $missao))->assertForbidden();
    }

    public function test_vincular_sem_aventureiro_retorna_erro(): void
    {
        $this->logarComoSecretario();
        $missao = $this->criarMissao();

        $response = $this->post(route('missoes.vincular', $missao), []);

        $response->assertSessionHasErrors(['id_aventureiro']);
    }

    public function test_vincular_usa_o_service_mockado(): void
    {
        $guilda = $this->criarGuilda();
        $this->logarComoSecretario($guilda);
        $aventureiro = $this->criarAventureiro($guilda->guilda_id, 'Aragorn');
        $missao = $this->criarMissao();

        $this->mock(MissoesCompletadasService::class, function (MockInterface $mock) use ($aventureiro, $missao) {
            $mock->shouldReceive('createMissaoCompleta')
                ->once()
                ->with([
                    'id_aventureiro' => $aventureiro->aventureiro_id,
                    'id_missao' => $missao->missao_id,
                ]);
        });

        $response = $this->post(route('missoes.vincular', $missao), [
            'id_aventureiro' => $aventureiro->aventureiro_id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('sucesso', 'Aragorn foi vinculado à missão.');
    }

    public function test_vincular_aventureiro_de_outra_guilda_retorna_404(): void
    {
        $minha = $this->criarGuilda('Minha Guilda');
        $outra = $this->criarGuilda('Outra Guilda');
        $this->logarComoSecretario($minha);
        $alheio = $this->criarAventureiro($outra->guilda_id, 'Legolas');
        $missao = $this->criarMissao();

        $this->mock(MissoesCompletadasService::class, function (MockInterface $mock) {
            $mock->shouldNotReceive('createMissaoCompleta');
        });

        $this->post(route('missoes.vincular', $missao), [
            'id_aventureiro' => $alheio->aventureiro_id,
        ])->assertNotFound();
    }

    public function test_trocar_passa_a_missao_para_outro_aventureiro(): void
    {
        $guilda = $this->criarGuilda();
        $this->logarComoSecretario($guilda);
        $antigo = $this->criarAventureiro($guilda->guilda_id, 'Aragorn');
        $novo = $this->criarAventureiro($guilda->guilda_id, 'Gimli');
        $missao = $this->criarMissao();

        $this->mock(MissoesCompletadasService::class, function (MockInterface $mock) use ($antigo, $novo, $missao) {
            $mock->shouldReceive('upadateMissaoAventureirotaById')
                ->once()
                ->with($missao->missao_id, $antigo->aventureiro_id, ['id_aventureiro' => $novo->aventureiro_id]);
        });

        $response = $this->post(route('missoes.trocar', $missao), [
            'antigo' => $antigo->aventureiro_id,
            'novo' => $novo->aventureiro_id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('sucesso', 'Missão passada de Aragorn para Gimli.');
    }

    public function test_desvincular_usa_o_service_mockado(): void
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

        $response = $this->delete(route('missoes.desvincular', [$missao, $aventureiro]));

        $response->assertRedirect();
        $response->assertSessionHas('sucesso', 'Vínculo removido.');
    }
}