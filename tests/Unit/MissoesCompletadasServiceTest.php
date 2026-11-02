<?php

namespace Tests\Unit;

use App\Interfaces\MissoesCompletadasRepositoryInterface;
use App\Services\MissoesCompletadasService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;

class MissoesCompletadasServiceTest extends TestCase
{
    public function test_calcula_xp_multiplicando_drop_pela_quantidade(): void
    {
        $this->mock(MissoesCompletadasRepositoryInterface::class);

        $service = app(MissoesCompletadasService::class);

        $this->assertSame(30, $service->calcularXp(10, 3));
    }

    public function test_grava_apenas_missoes_ainda_nao_concluidas(): void
    {
        $repo = $this->mock(MissoesCompletadasRepositoryInterface::class);

        $repo->shouldReceive('aventureiroExiste')->with(1)->once()->andReturn(true);
        $repo->shouldReceive('idsJaConcluidos')->with(1, [10, 20])->once()->andReturn([10]);
        $repo->shouldReceive('buscarDadosRecompensaVarias')->with([20])->once()->andReturn(collect([
            (object) ['missao_id' => 20, 'recompensa_ouro' => 100, 'quantidade_monstro' => 3, 'drop_xp' => 10],
        ]));
        $repo->shouldReceive('inserirVarias')->once()->with([
            ['id_aventureiro' => 1, 'id_missao' => 20, 'ouro_recebido' => 100, 'xp_recebido' => 30],
        ]);

        $total = app(MissoesCompletadasService::class)->createVariasMissoesCompletas(1, [10, 20]);

        $this->assertSame(1, $total);
    }
    public function test_nao_grava_nada_se_todas_ja_foram_concluidas(): void
    {
        $repo = $this->mock(MissoesCompletadasRepositoryInterface::class);

        $repo->shouldReceive('aventureiroExiste')->with(1)->once()->andReturn(true);
        $repo->shouldReceive('idsJaConcluidos')->with(1, [10])->once()->andReturn([10]);
        $repo->shouldNotReceive('buscarDadosRecompensaVarias');
        $repo->shouldNotReceive('inserirVarias');

        $total = app(MissoesCompletadasService::class)->createVariasMissoesCompletas(1, [10]);

        $this->assertSame(0, $total);
    }

    public function test_lanca_erro_se_o_aventureiro_nao_existe(): void
    {
        $repo = $this->mock(MissoesCompletadasRepositoryInterface::class);

        $repo->shouldReceive('aventureiroExiste')->with(999)->once()->andReturn(false);
        $repo->shouldNotReceive('inserirVarias');

        $this->expectException(ModelNotFoundException::class);

        app(MissoesCompletadasService::class)->createVariasMissoesCompletas(999, [10]);
    }
}

