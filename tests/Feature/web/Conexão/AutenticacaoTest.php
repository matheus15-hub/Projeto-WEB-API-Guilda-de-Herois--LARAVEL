<?php

namespace Tests\Feature\Web;

use Tests\TestCase;
use App\Models\User;
use App\Http\Controllers\Web\DashboardController;
use Illuminate\Contracts\Auth\StatefulGuard;
use Mockery;
use Mockery\MockInterface;

class AutenticacaoTest extends TestCase
{
    private function mockAuthGuard(bool $resultado): void
    {
        $auth = Mockery::mock(StatefulGuard::class);

        $auth->shouldReceive('attempt')
            ->once()
            ->andReturn($resultado);

        // O middleware 'guest' (RedirectIfAuthenticated) chama check() antes do controller.
        // Sem esta expectativa o Mockery lança BadMethodCallException.
        $auth->shouldReceive('check')
            ->zeroOrMoreTimes()
            ->andReturn(false);

        $auth->shouldReceive('guest')
            ->zeroOrMoreTimes()
            ->andReturn(true);

        $auth->shouldReceive('user')
            ->zeroOrMoreTimes()
            ->andReturn(null);

        $this->app['auth']->extend(
            'mock',
            function ($app, $name, $config) use ($auth) {
                return $auth;
            }
        );

        config([
            'auth.guards.web.driver' => 'mock',
        ]);

        $this->app['auth']->forgetGuards();
    }

    public function test_login_com_credenciais_validas(): void
    {
        $this->mockAuthGuard(true);

        $response = $this->post('/login', [
            'name' => 'jogador',
            'password' => 'senha123',
        ]);

        $response->assertRedirect(route('dashboard'));
    }

    public function test_login_com_credenciais_invalidas(): void
    {
        $this->mockAuthGuard(false);

        $response = $this->post('/login', [
            'name' => 'jogador',
            'password' => 'senha_errada',
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_dashboard_redireciona_usuario_nao_autenticado(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect(route('login'));
    }

    public function test_dashboard_permite_acesso_autenticado(): void
    {
        $this->actingAs(new User([
            'name' => 'jogador',
        ]));

        $this->mock(DashboardController::class, function (MockInterface $mock) {
            $mock->shouldReceive('index')
                ->once()
                ->andReturn(view('welcome'));
        });

        $response = $this->get('/dashboard');

        $response->assertOk();
        $response->assertViewIs('welcome');
    }

    public function test_logout_redireciona_usuario_nao_autenticado(): void
    {
        $response = $this->post('/logout');

        $response->assertRedirect(route('login'));
    }

    public function test_logout_encerra_sessao_e_redireciona(): void
    {
        $this->actingAs(new User([
            'name' => 'jogador',
        ]));

        $response = $this->post('/logout');

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}